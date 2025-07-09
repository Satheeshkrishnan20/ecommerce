<?php

namespace app\modules\admin\models;

use Yii;

/**
 * This is the model class for table "product".
 *
 * @property int $p_id
 * @property int $category_id
 * @property float $product_price
 * @property string $product_name
 * @property string $product_description
 * @property int $product_instock
 * @property string $product_image
 * @property int|null $status
 *
 * @property Category $category
 */
class Product extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'product';
    }

    /**
     * {@inheritdoc}
     */
public function rules()
{
    return [
        // Required fields for both create & update
        [['category_id', 'product_price', 'product_name', 'product_description', 'product_instock','min_quantity','max_quantity'], 'required', 'on' => ['create', 'update']],

        // Only required during create
        [['product_image'], 'required', 'on' => 'create'],

        // Optional (but safe) on update
        [['product_image'], 'safe', 'on' => 'update'],
         [['email'], 'unique', 'message' => 'This email has already account.'],

        // Common validation
        [['category_id', 'product_instock', 'status','min_quantity','max_quantity'], 'integer'],
        [['product_price'], 'number'],
        [['product_name'], 'string', 'max' => 200],
        [['product_description'], 'string', 'max' => 222],
        [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::class, 'targetAttribute' => ['category_id' => 'c_id']],
        ['product_name', 'validateUniqueProductName', 'on' => ['create', 'update']],
    ];
}



    public function scenarios(){
            $scenarios = parent::scenarios();
            $scenarios['create'] = ['category_id', 'product_price','product_name','product_description','product_instock','product_image','min_quantity','max_quantity']; 
            $scenarios['update'] = ['category_id', 'product_price','product_name','product_description','product_instock','min_quantity','max_quantity']; 
            return $scenarios;
    }

    public function validateUniqueProductName($attribute, $params, $validator)
        {
            $query = self::find()->where([$attribute => $this->$attribute]);

            // If it's update, exclude current ID
            if (!$this->isNewRecord) {
                $query->andWhere(['<>', 'p_id', $this->p_id]);
            }

            if ($query->exists()) {
                $this->addError($attribute, 'Product name already exists.');
            }
        }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'p_id' => 'P ID',
            'category_id' => 'Category ID',
            'product_price' => 'Product Price',
            'product_name' => 'Product Name',
            'product_description' => 'Product Description',
            'product_instock' => 'Product Instock',
            'product_image' => 'Product Image',
            'status' => 'Status',
            'min_quantity'=>'Min Quantity',
            'max_quantity'=>'Max Quantity'
        ];
    }

    /**
     * Gets query for [[Category]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::class, ['c_id' => 'category_id']);
    }

    

}
