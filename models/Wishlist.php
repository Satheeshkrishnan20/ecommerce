<?php

namespace app\models;

use Yii;
use yii\db\ActiveQuery;
use app\modules\admin\models\Product;

/**
 * This is the model class for table "wishlist".
 *
 * @property int $wishlist_id
 * @property int $user_id
 * @property int $product_id
 */
class Wishlist extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'wishlist';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'product_id'], 'required'],
            [['user_id', 'product_id'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'wishlist_id' => 'Wishlist ID',
            'user_id' => 'User ID',
            'product_id' => 'Product ID',
        ];
    }
     public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']); // Assuming User primary key is 'id'
    }
      public function getProduct()
    {
        
        return $this->hasOne(Product::class, ['p_id' => 'product_id']);
    }

    public static function getUserWishlist($userId)
        {
            return new \yii\data\ActiveDataProvider([
                'query' => self::find()
                    ->where(['user_id' => $userId, 'status' => 1])
                    ->with(['product.category']),
                'pagination' => ['pageSize' => 10],
            ]);
        }

}
