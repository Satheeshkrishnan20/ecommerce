<?php
namespace app\modules\admin\controllers;
use yii\web\Controller;
use app\models\User;
use yii\data\ActiveDataProvider;
use Yii;  
use app\components\MailHelper;


class UserController extends Controller{



        public function actionUser(){
            $model=new User;
            $model->scenario='usersearch';
            $this->layout='dashboard';

           

            $dataProvider=new ActiveDataProvider([
                'query'=>User::find()->where([
                    'usertype'=>1,'status'=>1,'is_verified'=>1
                ]),
                'pagination'=>[
                    'pageSize'=>10
                ]
                ]);

            return $this->render('user',[
                'model'=>$model,
                'dataProvider'=>$dataProvider
            ]);
        }



        
         public function actionCreate()
    {
        $this->layout = 'dashboard';

        $model = new User();
        $model->scenario = 'signup';

        if ($model->load(Yii::$app->request->post())) {
            
            $plainPassword = $model->password;

            if ($model->validate()) {
                $email = $model->email;
                Yii::$app->session->set('email', $email);

                $model->usertype = 1;
                $model->is_verified = 1;

               
                $model->password = Yii::$app->security->generatePasswordHash($model->password);

                if ($model->save(false)) { 

                    
                    $htmlBody = Yii::$app->mailer->render('account_created_html', [
                        'user' => $model, 
                        'rawPassword' => $plainPassword, 
                    ]);

                   

                    $sent = MailHelper::send(
                        $email,
                        'Your Amazon Account Has Been Created Successfully!', 
                        $htmlBody, 
                        Yii::$app->params['senderEmail'] ?? 'noreply@yourdomain.com', 
                        Yii::$app->params['senderName'] ?? 'Your App Name' 
                    );

                    $username = $model->username;
                    Yii::$app->session->set('username', $username);

                    if ($sent) {
                        Yii::$app->session->setFlash('success', 'User account created and welcome email sent successfully!');
                    } else {
                        Yii::$app->session->setFlash('warning', 'User account created, but failed to send welcome email. Please check server logs.');
                        Yii::error('Failed to send welcome email to ' . $email . ' for user ' . $username . '.');
                    }

                    return $this->redirect(['create']); 

                } else {
                    
                    Yii::$app->session->setFlash('error', 'Failed to save user account. Please try again.');
                    Yii::error('User save failed: ' . json_encode($model->getErrors()));
                }
            } else {
                
                Yii::$app->session->setFlash('error', 'Validation failed. Please correct the form errors.');
            }
        }

        return $this->render('create', [
            'model' => $model
        ]);
    }


    public function actionUpdate($id){
        $this->layout='dashboard';
        $model=User::findone($id);
        $model->scenario='signup';
        if($model->load(Yii::$app->request->post())){
            
                $model->save(false);
                return $this->redirect(['user']);
        }
        return $this->render('create',[
            'model'=>$model
        ]);
    }

public function actionDelete($id)
{
    $model = User::findOne($id);
    
    // Check if the user exists
    if ($model !== null) {
        $model->status = 0;  // Soft delete (set status to 0)

        // Check if the status was updated
        if ($model->save(false)) {
            // Check if it's a PJAX request (AJAX request)
            if (Yii::$app->request->isPjax) {
                // Return updated data provider (to reload grid view)
                $dataProvider = new ActiveDataProvider([
                    'query' => User::find()->where(['status' => 1]),  // Only active users
                    'pagination' => [
                        'pageSize' => 10
                    ]
                ]);
                
                // Render and send back only the partial grid view
                return $this->renderAjax('user', [
                    'dataProvider' => $dataProvider,
                ]);
            }

            // If it's not a PJAX request, just redirect to the user page
            return $this->redirect(['user']);
        }
    }

    // If user is not found or failed to save, show an error
    Yii::$app->session->setFlash('error', 'Error deleting user');
    return $this->redirect(['user']);
}



}

?>