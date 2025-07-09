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
        $userId = Yii::$app->session->get('user_id');

        $dataProvider = new ActiveDataProvider([
            'query' => Cart::find()
                ->where(['user_id' => $userId,'status'=>1])
                ->with(['product.category']),
            'pagination' => ['pageSize' => 3],
        ]);

        $dataProvider1 = new ActiveDataProvider([
            'query' => Cart::find()
                ->where(['user_id' => $userId,'status'=>1])
                ->with(['product.category']),
            'pagination' => ['pageSize' => 7],
        ]);

        return $this->render('cart', [
            'dataProvider' => $dataProvider,
            'dataProvider1' => $dataProvider1
        ]);
    }

    public function actionRemove($id){
        $cart = Cart::findOne($id);
         $cart->status=0;
         $cart->save();
         return $this->redirect(['cart']);
    }
}
