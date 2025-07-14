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
        if (Yii::$app->user->isGuest) {
             Yii::$app->session->setFlash('error', 'Login to Access.');
            return $this->redirect(['default/login'])->send();
        }
        return parent::beforeAction($action);
    }
 

   

   public function actionCategory()
        {
            $this->layout = 'header';

            $model = new Category();
            $categoryName = Yii::$app->request->post('category');
            $dataProvider = $model->searchByName($categoryName);

            return $this->render('category', [
                'dataProvider' => $dataProvider,
                'category' => $categoryName,
            ]);
        }



    


    public function actionCreatecategory(){
         $this->layout = 'header';

         $model=new Category();

          if ($model->load(Yii::$app->request->post()) && $model->validate()) {
             $model->status = 1;
           
       
        if ($model->save(false)) {
            Yii::$app->session->setFlash('success', 'Category created successfully.');
            return $this->redirect(['category/category']);
        }
            }

        return $this->render('Createcategory',[
            'model'=>$model
        ]);
    }

  

  

    public function actionUpdate($id){
        $this->layout='header';
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

  public function actionDelete($id)
{
    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

    if (!Yii::$app->request->isAjax || !Yii::$app->request->isPost) {
        return ['success' => false, 'message' => 'Invalid request.'];
    }

    $model = Category::findOne($id);

    if (!$model) {
        return ['success' => false, 'message' => 'Category not found.'];
    }
        $model->status=0;
    if ($model->save()) {
        return ['success' => true, 'message' => 'Category deleted successfully.'];
    } else {
        return ['success' => false, 'message' => 'Failed to delete category.'];
    }
}


}
