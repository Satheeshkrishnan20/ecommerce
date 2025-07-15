<?php

namespace app\controllers;

use yii\web\Controller;
use app\modules\admin\models\Product;
use app\models\Cart;
use yii\data\ActiveDataProvider;
use Yii;

class CartController extends Controller
{
    public function actionCart()
{
    if (Yii::$app->user->isGuest) {
        Yii::$app->session->setFlash('error', 'Login to access cart.');
        return $this->redirect(['home/login']);
    }

    $userId = Yii::$app->user->id;

    $dataProvider = Cart::getUserCart($userId, 3);   // smaller card view maybe
    $dataProvider1 = Cart::getUserCart($userId, 7);  // maybe sidebar or listing

    return $this->render('cart', [
        'dataProvider' => $dataProvider,
        'dataProvider1' => $dataProvider1,
    ]);
}


    public function actionRemove($id){
        $cart = Cart::findOne($id);
         $cart->status=0;
         $cart->save();
         Yii::$app->session->setFlash('success', 'Product Removed from Cart.');
         $userId=Yii::$app->user->id;
         $cartCount = Cart::find()->where(['user_id' => $userId,'status'=>1])->count();
        Yii::$app->helper->set('cart_item_count', $cartCount);
         return $this->redirect(['cart']);
    }
}
