<?php
namespace app\modules\admin\controllers;
use app\modules\admin\models\Auth;
use app\modules\admin\models\Product;
use app\modules\admin\models\Category;
use yii\web\UploadedFile;
use yii\web\Controller;
use yii\data\ActiveDataProvider;
use yii;

/**
 * Default controller for the `admin` module
 */
class CategoryController extends Controller
{
    // public $layout = 'admin';
    /**
     * Renders the index view for the module
     * @return string
     */

     public function beforeAction($action)
    {
        if (!Yii::$app->session->get('login')) {
            return $this->redirect(['default/login'])->send();
        }
        return parent::beforeAction($action);
    }
 
    public function actionCategory(){
        $this->layout='dashboard';
        $dataProvider=new ActiveDataProvider([
            'query'=>Category::find()
        ]);
        return $this->render('category',[
            'dataProvider'=>$dataProvider
        ]);
    }

    


    public function actionCreatecategory(){
         $this->layout = 'dashboard';

         $model=new Category();

          if ($model->load(Yii::$app->request->post()) && $model->validate()) {
             $model->status = 1;
           
       
        if ($model->save(false)) {
            Yii::$app->session->setFlash('success', 'Category created successfully.');
            return $this->refresh();
        }
            }

        return $this->render('Createcategory',[
            'model'=>$model
        ]);
    }

  

  

    public function actionUpdate($id){
        $this->layout='dashboard';
        $model = Category::findOne($id);

        if($model->load(yii::$app->request->post())){
            $model->save();
            Yii::$app->session->setFlash('success', 'Category updated successfully.');
            return $this->redirect(['category']);
        }

        return $this->render('createcategory',[
            'model'=>$model
        ]);
    }

    public function actionDelete($id){
        $this->layout='dashboard';
        $model = Category::findOne($id)->delete();
        Yii::$app->session->setFlash('success', 'Category deleted successfully.');

        return $this->redirect(['category']);
    }






}
