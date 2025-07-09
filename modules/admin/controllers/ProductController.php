<?php

namespace app\modules\admin\controllers;

use Yii;
use yii\web\Controller;
use yii\data\ActiveDataProvider;
use yii\web\UploadedFile;
use yii\helpers\FileHelper;
use app\modules\admin\models\Categorysearch;
use app\modules\admin\models\Product;
use app\modules\admin\models\Category;

class ProductController extends Controller
{
    public $layout = 'dashboard';

    public function beforeAction($action)
    {
        if (!Yii::$app->session->get('login')) {
            return $this->redirect(['default/login']);
        }
        return parent::beforeAction($action);
    }

    public function actionProduct()
    {
        $model = new Categorysearch();

        $query = Product::find()->joinWith('category');

        if ($model->load(Yii::$app->request->get()) && !empty($model->c_name)) {
            $query->andWhere(['like', 'category.c_name', $model->c_name]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 10],
            'sort' => ['defaultOrder' => ['p_id' => SORT_DESC]],
        ]);

        return $this->render('product', [
            'dataProvider' => $dataProvider,
            'model' => $model
        ]);
    }

    public function actionCreate()
    {
        $model = new Product();

        if ($model->load(Yii::$app->request->post())) {
            $model->product_image = UploadedFile::getInstance($model, 'product_image');

            if ($model->validate()) {
                $imageDir = Yii::getAlias('@webroot/images/products/');
                if (!is_dir($imageDir)) {
                    FileHelper::createDirectory($imageDir, 0775, true);
                }

                if ($model->product_image) {
                    $imageName = uniqid() . '.' . $model->product_image->extension;
                    $savePath = $imageDir . $imageName;

                    if ($model->product_image->saveAs($savePath)) {
                        $model->product_image = $imageName;
                    } else {
                        Yii::$app->session->setFlash('error', 'Failed to upload image.');
                        return $this->render('create', ['model' => $model]);
                    }
                } else {
                    $model->product_image = null;
                }

                if ($model->save()) {
                    Yii::$app->session->setFlash('success', 'Product created successfully.');
                    return $this->redirect(['create']);
                } else {
                    Yii::$app->session->setFlash('error', 'Failed to save product.');
                }
            } else {
                Yii::$app->session->setFlash('error', 'Validation failed.');
            }
        }

        return $this->render('create', ['model' => $model]);
    }

    public function actionUpdate($id)
    {
        $model = Product::findOne($id);

        if ($model === null) {
            throw new \yii\web\NotFoundHttpException('The requested product does not exist.');
        }

        $oldImage = $model->product_image;

        if ($model->load(Yii::$app->request->post())) {
            $uploadedFile = UploadedFile::getInstance($model, 'product_image');

            $imageDir = Yii::getAlias('@webroot/images/products/');
            if (!is_dir($imageDir)) {
                FileHelper::createDirectory($imageDir, 0775, true);
            }

            if ($uploadedFile) {
                $imageName = uniqid() . '.' . $uploadedFile->extension;
                $savePath = $imageDir . $imageName;

                if ($uploadedFile->saveAs($savePath)) {
                    $model->product_image = $imageName;

                    if ($oldImage && file_exists($imageDir . $oldImage)) {
                        unlink($imageDir . $oldImage);
                    }
                } else {
                    Yii::$app->session->setFlash('error', 'Failed to upload new image.');
                    $model->product_image = $oldImage;
                }
            } else {
                $model->product_image = $oldImage;
            }

            if ($model->validate() && $model->save()) {
                Yii::$app->session->setFlash('success', 'Product updated successfully.');
                return $this->redirect(['product']);
            } else {
                Yii::$app->session->setFlash('error', 'Failed to update product.');
            }
        }

        return $this->render('create', ['model' => $model]);
    }

    public function actionDelete($id)
    {
        $model = Product::findOne($id);

        if ($model === null) {
            throw new \yii\web\NotFoundHttpException('The requested product does not exist.');
        }

        $imagePath = Yii::getAlias('@webroot/images/products/') . $model->product_image;

        if ($model->product_image && file_exists($imagePath)) {
            unlink($imagePath);
        }

        if ($model->delete()) {
            Yii::$app->session->setFlash('success', 'Product deleted successfully.');
        } else {
            Yii::$app->session->setFlash('error', 'Failed to delete product.');
        }

        return $this->redirect(['product']);
    }

    public function actionView($id)
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Product::find()->where(['p_id' => $id])
        ]);

        return $this->render('view', [
            'dataProvider' => $dataProvider
        ]);
    }
}
