<?php
namespace app\modules\admin\controllers;
use yii\web\Controller;
use app\models\User;
use Yii;
use yii\data\ActiveDataProvider;

class AdminController extends Controller{

     public function beforeAction($action)
    {
        if (!Yii::$app->session->get('login')) {
            return $this->redirect(['default/login']);
        }
        return parent::beforeAction($action);
    }

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
                if($model->save()){
                    Yii::$app->session->setFlash('success', 'Account Creation Successfull');
                    return $this->redirect(['admin/admin']);
                }
        }


        return $this->render('create',[
            'model'=>$model
        ]);
    }

    public function actionUpdate($id){

        $this->layout='dashboard';
        $model=User::findOne($id);
        $model->scenario='createadmin';
        

        if($model->load(Yii::$app->request->post())&& $model->validate()){
            if($model->save()){
                Yii::$app->session->setFlash('success', 'Account Updation Successfull');
                return $this->redirect(['admin/admin']);
            }
        }
        
        return $this->render('create',[
            'model'=>$model
        ]);

    }

    public function actionDelete($id){

        $admin=User::findOne($id);

        $admin->status=0;
        $admin->username = 'Account deleted @'.date('Y-m-d H:i:s').' - '.$admin->username;
        $admin->save(false);
        return $this->redirect(['admin']);

    }

    public function actionView($id) //This is for the rbac controller
        {
            $this->layout = 'dashboard';

            $model = User::findOne($id); 

            if (Yii::$app->request->isPost) {
                
                $rbacArray = Yii::$app->request->post('rbac', []); 

               
                $model->rbac = json_encode($rbacArray);

                if ($model->save(false)) {
                    Yii::$app->session->setFlash('success', 'Permissions saved successfully!');
                    return $this->redirect(['admin']);
                } else {
                    Yii::$app->session->setFlash('error', 'Failed to save permissions!');
                }

                return $this->refresh(); 
            }

            return $this->render('view', [
                'model' => $model
            ]);
        }


}


?>