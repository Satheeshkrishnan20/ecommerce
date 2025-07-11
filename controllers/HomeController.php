<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\User;
use app\models\Cart;
use app\modules\admin\models\Product;
use app\modules\admin\models\Category;
use yii\data\ActiveDataProvider;
use app\components\Helper;

class HomeController extends Controller
{
    


        public function actionHome()
        {
            $request = Yii::$app->request;
            $selectedSeoUrls = $request->get('seourl', []);

            if (!is_array($selectedSeoUrls)) {
                $selectedSeoUrls = [$selectedSeoUrls];
            }

        
            $category = Category::find()
                ->innerJoinWith('products')
                ->where(['category.status' => 1])
                ->groupBy('category.c_id')
                ->all();

            
            $query = Product::find()
                ->alias('p') 
                ->innerJoinWith(['category c']) 
                ->where(['p.status' => 1]); 

        
            if (!empty($selectedSeoUrls)) {
                $query->andWhere(['c.seourl' => $selectedSeoUrls]);
            }

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => ['pageSize' => 10],
            ]);

            return $this->render('home', [
                'dataProvider' => $dataProvider,
                'category' => $category,
                'selectedSeoUrls' => $selectedSeoUrls,
            ]);
        }




    public function actionSignup()
    {
        $model = new User();
        $model->scenario = 'signup';

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            // Yii::$app->session->set('username', $model->username);
            Yii::$app->session->set('email', $model->email);

            try {
                if ($model->sendOtp()) {
                    return $this->redirect(['otp']);
                } else {
                    Yii::$app->session->setFlash('error', 'Could not send OTP. Please try again.');
                }
            } catch (\Exception $e) {
                Yii::$app->session->setFlash('error', 'Error: ' . $e->getMessage());
            }
        }

        return $this->render('signup', ['model' => $model]);
    }


    public function actionOtp()
    {
        $model = new User();
        $model->scenario = 'otp';
        $username = Yii::$app->session->get('username');

        if (!$username) {
            Yii::$app->session->setFlash('error', 'Session expired. Please sign up again.');
            return $this->redirect(['signup']);
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $user = User::findOne(['username'=>$username]);
                    

            if (!$user) {
                Yii::$app->session->setFlash('error', 'User not found.');
                return $this->redirect(['signup']);
            }

          $submittedOtp = $model->getOtp();

            try {
                $now = new \DateTime('now', new \DateTimeZone('Asia/Kolkata'));
                $otpExpiry = new \DateTime($user->otp_expiry, new \DateTimeZone('Asia/Kolkata'));
            } catch (\Exception $e) {
                Yii::$app->session->setFlash('error', 'Invalid OTP expiry format.');
                return $this->refresh();
            }

            if ((string)$submittedOtp !== (string)$user->otp) {
                Yii::$app->session->setFlash('error', 'Incorrect OTP entered.');
            } elseif ($now > $otpExpiry) {
                Yii::$app->session->setFlash('error', 'OTP has expired. Please resend.');
            } else {
                $user->is_verified = 1;
                $user->otp = null;
                $user->otp_expiry = null;
                $user->save(false);

                Yii::$app->session->setFlash('success', 'OTP verified successfully!');
                return $this->redirect(['login']);
            }
        }

        return $this->render('otp', ['model' => $model]);
    }

   public function actionResend()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $email = Yii::$app->session->get('email'); // Make sure this key is set in session
        if (!$email) {
            return ['success' => false, 'message' => 'Email not found in session.'];
        }

        $user = User::findOne(['email' => $email]);

        if ($user) {
            $otp = $user->getOtp();
            $user->otp = $otp;
            $user->otp_expiry = date('Y-m-d H:i:s', strtotime('+5 minutes'));
            $user->save(false);

            Helper::send(
                $email,
                'Resend OTP',
                '<b style="font-size:18px;">' . $otp . '</b>'
            );

            return ['success' => true];
        }

        return ['success' => false, 'message' => 'User not found for this email'];
    }


    public function actionLogin()
    {
        $model = new User();
        $model->scenario = 'login';

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $submittedUsername = $model->username;
            $submittedPassword = $model->password;

            $user = User::find()
                ->where(['username' => $submittedUsername, 'is_verified' => 1, 'status' => 1])
                ->one();

            if ($user && Yii::$app->security->validatePassword($submittedPassword, $user->password)) {
                Yii::$app->session->set('isLoggedIn', true);
                Yii::$app->session->set('username', $user->username);
                Yii::$app->session->set('user_id', $user->id);
                Yii::$app->session->setFlash('success', 'Login successful!');
                return $this->redirect(['home']);
            }

            Yii::$app->session->setFlash('error', 'Invalid username or password.');
        }

        return $this->render('login', ['model' => $model]);
    }

    public function actionLogout()
    {
        Yii::$app->session->destroy();
        return $this->redirect(['home']);
    }

    public function actionAdd()
    {
        $productId = Yii::$app->request->post('product_id');
        $quantity = Yii::$app->request->post('quantity', 1);
        $userId = Yii::$app->session->get('user_id');

        if (!$userId) {
            Yii::$app->session->setFlash('error', 'Login to add items to cart.');
            return $this->redirect(['login']);
        }

        $cartItem = Cart::findOne(['user_id' => $userId, 'product_id' => $productId]);

        if ($cartItem) {
            $cartItem->quantity += $quantity;
        } else {
            $cartItem = new Cart([
                'user_id' => $userId,
                'product_id' => $productId,
                'quantity' => $quantity
            ]);
        }

        $cartItem->save(false);
        $cartCount = Cart::find()->where(['user_id' => $userId])->count();
        Yii::$app->session->set('cart_item_count', $cartCount);
        Yii::$app->session->setFlash('success', 'Product added to cart!');

        return $this->goBack();
    }
}
