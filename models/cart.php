<?php

namespace app\models;
use app\modules\admin\models\Product;

use Yii;

/**
 * This is the model class for table "cart".
 *
 * @property int $cart_id
 * @property int $user_id
 * @property int $product_id
 * @property int $quantity
 */
class Cart extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'cart';
    }

    public function rules()
    {
        return [
            [['user_id', 'product_id', 'quantity'], 'required'],
            [['user_id','product_id', 'quantity'], 'integer'],
            
        ];
    }

    public static function getUserCart($userId, $pageSize = 3)
        {
            return new \yii\data\ActiveDataProvider([
                'query' => self::find()
                    ->where(['user_id' => $userId, 'status' => 1])
                    ->with(['product.category']),
                'pagination' => ['pageSize' => $pageSize],
            ]);
        }

        // app/models/Cart.php

      

       



    public function getProduct()
    {
        return $this->hasOne(Product::class, ['p_id' => 'product_id']);
    }
}
