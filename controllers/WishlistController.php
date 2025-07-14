<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\Wishlist;
use yii\data\ActiveDataProvider;
use app\modules\admin\models\Product;
use app\modules\admin\models\Category;


class WishlistController extends Controller
{
   public function actionAdd()
                {
                    //  $userId = Yii::$app->session->get('user_id');
                    $userId=Yii::$app->user->id;
                     if (Yii::$app->user->isGuest) {
                        Yii::$app->session->setFlash('error', 'Login to access Wishlist.');
                        return $this->redirect(['home/login']);
                    }

                    $user_id = Yii::$app->user->id;
                    // $user_id = Yii::$app->session->get('user_id');
                    $product_id = Yii::$app->request->post('product_id');

                    // Check if product already exists in wishlist
                    $existing = Wishlist::find()
                        ->where(['user_id' => $user_id, 'product_id' => $product_id])
                        ->one();

                    if ($existing) {
                        Yii::$app->session->setFlash('info', 'Product already in the wishlist!');
                        return $this->redirect(['home/home']); 
                    }

                    // Add to wishlist
                    $wishlist = new Wishlist();
                    $wishlist->user_id = $user_id;
                    $wishlist->product_id = $product_id;
                    $wishlist->save(false); 

                    Yii::$app->session->setFlash('success', 'Product added to wishlist!');
                    return $this->redirect(['home/home']);
                }

        public function actionWishlist()
        {
            if (Yii::$app->user->isGuest) {
                Yii::$app->session->setFlash('error', 'Login to view wishlist.');
                return $this->redirect(['home/login']);
            }

            $dataProvider = Wishlist::getUserWishlist(Yii::$app->user->id);

            return $this->render('wishlist', [
                'dataProvider' => $dataProvider
            ]);
        }


    public function actionDelete($id){
         $wishlist = Wishlist::findOne($id);
         $wishlist->status=0;
         $wishlist->delete();
         return $this->redirect(['wishlist']);
    }


}


?>