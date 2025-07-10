<?php

namespace app\modules\admin\controllers;

use app\models\User;
use app\modules\admin\models\Product; // Assuming Product model is used for counts
use app\modules\admin\models\Category; // Assuming Category model is used for counts
use yii\web\UploadedFile; // Not used in this controller snippet, but kept for context
use yii\web\Controller;
use yii\data\ActiveDataProvider; // Not used in this controller snippet, but kept for context
use Yii; // Correctly use Yii for clarity

/**
 * Default controller for the `admin` module
 */
class DefaultController extends Controller
{
    // public $layout = 'admin'; // Commented out as dashboard is set in actionDashboard

    /**
     * Handles the login process.
     * @return string|\yii\web\Response
     */
   public function actionLogin()
{
    $model = new User;
    $model->scenario = 'admin';

    if ($model->load(Yii::$app->request->post())) {
        $user = User::find()->where([
            'username' => $model->username,
            'password' => $model->password,
        ])->one();

        if ($user) {
            if ($user->usertype == 1) {
                // ❌ Block usertype 1
                Yii::$app->session->setFlash('error', 'Access denied for this user type.');
                return $this->redirect(['login']);
            }

            // ✅ Common session data
            Yii::$app->session->set('login', true);
            Yii::$app->session->set('user_id', $user->id);
            Yii::$app->session->set('username', $user->username);
            Yii::$app->session->set('usertype', $user->usertype);

            // ✅ For Admin (type 2), set RBAC
            if ($user->usertype == 2) {
                $rbac = json_decode($user->rbac, true);
                Yii::$app->session->set('rbac', is_array($rbac) ? $rbac : []);
            }

            Yii::$app->session->setFlash('success', 'Welcome back, ' . $user->username . '!');
            return $this->redirect(['dashboard']);
        } else {
            Yii::$app->session->setFlash('error', 'Invalid username or password.');
        }
    }

    return $this->render('login', ['model' => $model]);
}


    /**
     * Renders the dashboard view.
     * @return string|\yii\web\Response
     */
 public function actionDashboard()
{
    $login = Yii::$app->session->get('login');

    if ($login) {
        $this->layout = 'dashboard';

        
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
    } else {
        Yii::$app->session->setFlash('info', 'Please log in to access the dashboard.');
        return $this->redirect(['default/login']);
    }
}

        public function actionCreatecustomer(){
            $model=new Auth();
            $this->layout='dashboard';
             return $this->render('/home/signup', [ 
            'model' => $model,
        ]);
        }


    /**
     * Handles the logout process.
     * @return \yii\web\Response
     */
    public function actionLogout()
    {
        // Clear all session data
        Yii::$app->session->destroy();

        // Set a success flash message for logout
        Yii::$app->session->setFlash('success', 'You have been successfully logged out.');

        // Redirect to login page
        return $this->redirect(['default/login']);
    }
}