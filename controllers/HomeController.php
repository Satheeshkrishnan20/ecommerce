<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\User;
use app\models\Cart;
use app\modules\admin\models\Product;
use app\modules\admin\models\Category;
use yii\data\ActiveDataProvider;

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

    if (!Yii::$app->user->isGuest) {
                
                return $this->redirect(['home/home']);
        }

    if ($model->load(Yii::$app->request->post()) && $model->validate()) {
        try {
            if ($model->sendOtp()) {
                // Store plain otpkey in session, send hashed to frontend
                Yii::$app->session->set('plainOtpKey', $model->otpkey); // plain saved in session
                Yii::$app->session->set('hashedOtpKey', Yii::$app->security->generatePasswordHash($model->otpkey));

                return $this->redirect(['verifyotp']);
            } else {
                Yii::$app->helper->setFlash('error', 'Could not send OTP. Please try again.');
            }
        } catch (\Exception $e) {
            Yii::$app->helper->setFlash('error', 'Error: ' . $e->getMessage());
        }
    }

    return $this->render('signup', ['model' => $model]);
}


    public function actionVerifyotp()
{
     if (!Yii::$app->user->isGuest) {
                
                return $this->redirect(['home/home']);
        }
    $model = new User();
    $model->scenario = 'otp';

    $hashedOtpKey = Yii::$app->session->get('hashedOtpKey');
    $plainOtpKey = Yii::$app->session->get('plainOtpKey');

    if (!$plainOtpKey || !$hashedOtpKey) {
        Yii::$app->helper->setFlash('error', 'Session expired. Please sign up again.');
        return $this->redirect(['signup']);
    }

    // Set hashedOtpKey to model so it's stored in hidden field
    $model->otpkey = $hashedOtpKey;

    if ($model->load(Yii::$app->request->post()) && $model->validate()) {
        $submittedOtp = $model->getOtp();

        // Now find user using the plain key
        $user = User::find()->where([
            'otpkey' => $plainOtpKey,
            'is_verified' => 0
        ])->one();

        if (!$user) {
            Yii::$app->helper->setFlash('error', 'User not found or already verified.');
            return $this->redirect(['signup']);
        }

        // Check OTP match
        if ((string)$submittedOtp !== (string)$user->otp) {
            Yii::$app->helper->setFlash('error', 'Incorrect OTP entered.');
        } elseif ((new \DateTime('now', new \DateTimeZone('Asia/Kolkata'))) > new \DateTime($user->otp_expiry, new \DateTimeZone('Asia/Kolkata'))) {
            Yii::$app->helper->setFlash('error', 'OTP has expired. Please resend.');
        } else {
            $user->is_verified = 1;
            $user->otp = null;
            $user->otp_expiry = null;
            $user->otpkey = null;
            $user->save(false);

            Yii::$app->session->remove('plainOtpKey');
            Yii::$app->session->remove('hashedOtpKey');

            Yii::$app->helper->setFlash('success', 'OTP verified successfully!');
            return $this->redirect(['login']);
        }
    }

    return $this->render('verifyotp', ['model' => $model]);
}

        public function actionResend()
        {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            $plainOtpKey = Yii::$app->session->get('plainOtpKey');

            if (!$plainOtpKey) {
                return ['success' => false, 'message' => 'Session expired. Please sign up again.'];
            }

            $user = User::find()->where([
                'otpkey' => $plainOtpKey,
                'is_verified' => 0
            ])->one();

            if ($user) {
                $otp = $user->generateOtp();
                $user->otp = $otp;
                $user->otp_expiry = date('Y-m-d H:i:s', strtotime('+5 minutes'));

                // Generate a new otp key
                $newPlainOtpKey = $user->generateCustomOtpKey();
                $user->otpkey = $newPlainOtpKey;

                $user->save(false);

                // Update session
                Yii::$app->session->set('plainOtpKey', $newPlainOtpKey);
                Yii::$app->session->set('hashedOtpKey', Yii::$app->security->generatePasswordHash($newPlainOtpKey));

                Helper::send($user->email, 'Resend OTP', '<b style="font-size:18px;">' . $otp . '</b>');
                Yii::$app->helper->setFlash('success', 'OTP Resent successfully!');

                return ['success' => true];
            }

            return ['success' => false, 'message' => 'User not found or already verified.'];
        }


    public function actionLogin()
    {
        $model = new User();
        $model->scenario = 'login';

        if (!Yii::$app->user->isGuest) {
                
                return $this->redirect(['home/home']);
        }

        

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $submittedUsername = $model->username;
            $submittedPassword = $model->password;

            $user = User::find()
                ->where(['username' => $submittedUsername, 'is_verified' => 1, 'status' => 1, 'usertype' => 1])
                ->one();

            if ($user && Yii::$app->security->validatePassword($submittedPassword, $user->password)) {
                Yii::$app->user->login($user);
                $userId=$user->id;
                $cartCount = Cart::find()->where(['user_id' => $userId,'status'=>1])->count();
                Yii::$app->helper->set('cart_item_count', $cartCount);

                Yii::$app->helper->set('username', $model->username);
                Yii::$app->helper->setFlash('success', 'Login successful!');
                return $this->redirect(['home']);
            }

            Yii::$app->helper->setFlash('error', 'Invalid username or password.');
        }

        return $this->render('login', ['model' => $model]);
    }

    public function actionLogout()
    {
        Yii::$app->helper->destroy();
        return $this->redirect(['home']);
    }

    public function actionAdd()
    {
        $productId = Yii::$app->request->post('product_id');
        $quantity = Yii::$app->request->post('quantity', 1);
        $userId = Yii::$app->user->id ?? null;

        if (!$userId) {
            Yii::$app->helper->setFlash('error', 'Login to add items to cart.');
            return $this->redirect(['login']);
        }

        $cartItem = Cart::findOne(['user_id' => $userId, 'product_id' => $productId,'status'=>1]);

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

        $cartCount = Cart::find()->where(['user_id' => $userId,'status'=>1])->count();
        Yii::$app->helper->set('cart_item_count', $cartCount);
        Yii::$app->helper->setFlash('success', 'Product added to cart!');

        return $this->goBack();
    }
}
