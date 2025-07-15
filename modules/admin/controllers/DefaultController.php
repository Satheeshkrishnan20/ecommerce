<?php

namespace app\modules\admin\controllers;

use app\components\Helper;
use app\models\User;
use app\modules\admin\models\Product;
use app\modules\admin\models\Category;
use yii\web\Controller;
use Yii;
use yii\helpers\Html;

class DefaultController extends Controller
{
    /**
     * Handles the admin login process.
     */
   public function actionLogin()
{
    $model = new User(['scenario' => 'admin']);

    if (!Yii::$app->user->isGuest) {
        return $this->redirect(['dashboard']);
    }

    if ($model->load(Yii::$app->request->post())) {
        if ($model->Adminlogin()) {
            Yii::$app->helper->setFlash('success', 'Welcome back, ' . Yii::$app->user->identity->username . '!');
            return $this->redirect(['dashboard']);
        } else {
            Yii::$app->helper->setFlash('error', 'invalid username or password');
        }
    }

    return $this->render('login', ['model' => $model]);
}


    /**
     * Renders the admin dashboard view.
     */
    public function actionDashboard()
    {
      
        
        $login=(!Yii::$app->user->isGuest)?true:false;

        if ($login) {
            $this->layout = 'header';

            $categoryCount = Category::find()->count();
            $productCount = Product::find()->count();

            $productPerCategory = (new \yii\db\Query())
                ->select(['category.c_name AS category_name', 'COUNT(product.p_id) AS product_count'])
                ->from('product')
                ->leftJoin('category', 'product.category_id = category.c_id')
                ->groupBy('category.c_id')
                ->all();

            $latestProducts = Product::find()
                ->orderBy(['p_id' => SORT_DESC])
                ->limit(5)
                ->all();

            return $this->render('dashboard', [
                'cat' => $categoryCount,
                'pro' => $productCount,
                'chartData' => $productPerCategory,
                'latestProducts' => $latestProducts,
            ]);
        }

        Yii::$app->helper->setFlash('info', 'Please log in to access the dashboard.');
        return $this->redirect(['default/login']);
    }

    /**
     * Handles the admin logout process.
     */
    public function actionLogout()
    {
        Yii::$app->helper->destroy(); 
        Yii::$app->helper->setFlash('success', 'You have been successfully logged out.');
        return $this->redirect(['default/login']);
    }
}
