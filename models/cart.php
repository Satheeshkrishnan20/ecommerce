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

    public function getProduct()
    {
        return $this->hasOne(Product::class, ['p_id' => 'product_id']);
    }
}
