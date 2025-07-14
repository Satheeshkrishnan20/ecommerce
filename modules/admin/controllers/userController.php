<?php

namespace app\modules\admin\controllers;

use yii\web\Controller;
use app\models\User;
use yii\data\ActiveDataProvider;
use Yii;
use app\components\Helper;

class UserController extends Controller
{
    public function beforeAction($action)
    {
        if (Yii::$app->user->isGuest) {
            Yii::$app->helper->setFlash('error', 'Login to Access.');
            return $this->redirect(['default/login'])->send();
        }
        return parent::beforeAction($action);
    }

    public function actionUser()
    {
        $model = new User;
        $model->scenario = 'usersearch';
        $this->layout = 'header';

        $dataProvider = User::getAllUser();

        return $this->render('user', [
            'model' => $model,
            'dataProvider' => $dataProvider
        ]);
    }

    public function actionCreate()
    {
        $this->layout = 'header';

        $model = new User();
        $model->scenario = 'signup';

        if ($model->load(Yii::$app->request->post())) {
            $plainPassword = $model->password;

            if ($model->validate()) {
                $email = $model->email;
                Yii::$app->helper->set('email', $email);

                $model->usertype = 1;
                $model->is_verified = 1;
                $model->password = Yii::$app->security->generatePasswordHash($model->password);

                if ($model->save(false)) {
                    $htmlBody = Yii::$app->mailer->render('account_created_html', [
                        'user' => $model,
                        'rawPassword' => $plainPassword,
                    ]);

                    $sent = Helper::send(
                        $email,
                        'Your Amazon Account Has Been Created Successfully!',
                        $htmlBody,
                        Yii::$app->params['senderEmail'] ?? 'noreply@yourdomain.com',
                        Yii::$app->params['senderName'] ?? 'Your App Name'
                    );

                    Yii::$app->helper->set('username', $model->username);

                    if ($sent) {
                        Yii::$app->helper->setFlash('success', 'User account created and welcome email sent successfully!');
                    } else {
                        Yii::$app->helper->setFlash('warning', 'User account created, but failed to send welcome email. Please check server logs.');
                        Yii::error('Failed to send welcome email to ' . $email . ' for user ' . $model->username . '.');
                    }

                    return $this->redirect(['user']);
                } else {
                    Yii::$app->helper->setFlash('error', 'Failed to save user account. Please try again.');
                    Yii::error('User save failed: ' . json_encode($model->getErrors()));
                }
            } else {
                Yii::$app->helper->setFlash('error', 'Validation failed. Please correct the form errors.');
            }
        }

        return $this->render('create', [
            'model' => $model
        ]);
    }

    public function actionUpdate($id)
    {
        $this->layout = 'header';
        $model = User::findOne($id);
        $model->scenario = 'signup';

        if ($model->load(Yii::$app->request->post())) {
            $model->save(false);
            return $this->redirect(['user']);
        }

        return $this->render('create', [
            'model' => $model
        ]);
    }

 public function actionDelete($id)
{
    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

    if (!Yii::$app->request->isAjax || !Yii::$app->request->isPost) {
        return ['success' => false, 'message' => 'Invalid request.'];
    }

    $model = User::findOne($id);

    if (!$model) {
        return ['success' => false, 'message' => 'User not found.'];
    }

    $model->status = 0;
    $model->username = 'Account deleted @' . date('Y-m-d H:i:s') . ' - ' . $model->username;

    if ($model->save(false)) {
        return ['success' => true, 'message' => 'User deleted successfully.'];
    }

    return ['success' => false, 'message' => 'Failed to delete user.'];
}

}
