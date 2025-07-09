<?php
namespace app\modules\admin\controllers;
use yii\web\Controller;
use app\models\User;
use Yii;
use yii\data\ActiveDataProvider;

class AdminController extends Controller{

    public function actionAdmin(){

       $dataProvider=new ActiveDataProvider([
        'query'=>User::find()->where(['usertype'=>2,'status'=>1]),
        'pagination'=>[
                'pageSize'=>10
            ]
       ]);
        $this->layout='dashboard';
       
        return $this->render('admin',[
            'dataProvider'=>$dataProvider,
            
        ]);
    }
    public function actionCreate(){
        $model=new User();
        $this->layout='dashboard';
        $model->scenario='createadmin';

        if($model->load(Yii::$app->request->post()) && $model->validate()){
                $model->usertype=2;
                $model->is_verified=1;
                $model->save(false);
        }


        return $this->render('create',[
            'model'=>$model
        ]);
    }

    public function actionUpdate($id){

        $this->layout='dashboard';
        $model=User::findOne($id);
        if($model->load(Yii::$app->request->post())&& $model->validate()){
            $model->save(false);
             return $this->redirect(['admin']);
        }
        return $this->render('create',[
            'model'=>$model
        ]);

    }

    public function actionDelete($id){

        $admin=User::findOne($id);

        $admin->status=0;
        $admin->save(false);
        return $this->redirect(['admin']);

    }

}


?>