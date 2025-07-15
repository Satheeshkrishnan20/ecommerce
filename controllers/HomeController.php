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

    public function actionError()
        {
            $exception = Yii::$app->errorHandler->exception;

            if ($exception !== null) {
                return $this->render('error', ['exception' => $exception]);
            }
        }
    
    public function actionHome()
    {
        // throw new \yii\web\NotFoundHttpException('This page does not exist.');
        $searchModel = new Product();
        $dataProvider = $searchModel->search(Yii::$app->request->get());
        $category = Category::getActiveCategory();


        return $this->render('home', [
            'dataProvider' => $dataProvider,
            'category' => $category,
            'selectedSeoUrls' => Yii::$app->request->get('seourl', []),
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
                    Yii::$app->helper->set('plainOtpKey', $model->otpkey);
                    Yii::$app->helper->set('hashedOtpKey', Yii::$app->security->generatePasswordHash($model->otpkey));
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

        $hashedOtpKey = Yii::$app->helper->get('hashedOtpKey');
        $plainOtpKey = Yii::$app->helper->get('plainOtpKey');

        if (!$plainOtpKey || !$hashedOtpKey) {
            Yii::$app->helper->setFlash('error', 'Session expired. Please sign up again.');
            return $this->redirect(['signup']);
        }

        $model->otpkey = $hashedOtpKey;

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $submittedOtp = $model->getOtp();

            $user = User::find()->where([
                'otpkey' => $plainOtpKey,
                'is_verified' => 0
            ])->one();

            if (!$user) {
                Yii::$app->helper->setFlash('error', 'User not found or already verified.');
                return $this->redirect(['signup']);
            }

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

                Yii::$app->helper->remove('plainOtpKey');
                Yii::$app->helper->remove('hashedOtpKey');

                Yii::$app->helper->setFlash('success', 'OTP verified successfully!');
                return $this->redirect(['login']);
            }
        }

        return $this->render('verifyotp', ['model' => $model]);
    }

    public function actionResend()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $plainOtpKey = Yii::$app->helper->get('plainOtpKey');

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

            $newPlainOtpKey = $user->generateCustomOtpKey();
            $user->otpkey = $newPlainOtpKey;
            $user->save(false);

            Yii::$app->helper->set('plainOtpKey', $newPlainOtpKey);
            Yii::$app->helper->set('hashedOtpKey', Yii::$app->security->generatePasswordHash($newPlainOtpKey));

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

            if ($model->load(Yii::$app->request->post()) && $model->login()) {
                Yii::$app->helper->setFlash('success', 'Login successful!');
                return $this->redirect(['home']);
            }

            return $this->render('login', ['model' => $model]);
        }


    public function actionLogout()
    {
        Yii::$app->helper->destroy();
        Yii::$app->user->logout();
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

    // ✅ Fetch the product with max_quantity
    $product = Product::findOne(['p_id' => $productId, 'status' => 1]);
    if (!$product) {
        Yii::$app->helper->setFlash('error', 'Product not found.');
        return $this->goBack();
    }

    $maxQuantity = $product->max_quantity ?? 10; // Fallback if not set

    $cartItem = Cart::findOne(['user_id' => $userId, 'product_id' => $productId, 'status' => 1]);

    if ($cartItem) {
        $newQuantity = $cartItem->quantity + $quantity;
        if ($newQuantity > $maxQuantity) {
            Yii::$app->helper->setFlash('error', "Only $maxQuantity units allowed per user for this product.");
            return $this->goBack();
        }
        $cartItem->quantity = $newQuantity;
    } else {
        if ($quantity > $maxQuantity) {
            Yii::$app->helper->setFlash('error', "You can add only up to $maxQuantity units of this product.");
            return $this->goBack();
        }
        $cartItem = new Cart([
            'user_id' => $userId,
            'product_id' => $productId,
            'quantity' => $quantity,
            'status' => 1,
        ]);
    }

    $cartItem->save(false);

    $cartCount = Cart::find()->where(['user_id' => $userId, 'status' => 1])->count();
    Yii::$app->helper->set('cart_item_count', $cartCount);
    Yii::$app->helper->setFlash('success', 'Product added to cart!');

    return $this->goBack();
}
}
