<?php
namespace app\modules\admin\models;

use Yii;
use yii\db\ActiveRecord; 


class Category extends ActiveRecord
{
   
     
    public static function tableName()
    {
        return 'category';
    }

   
    public function rules()
    {
        return [
            [['seourl'], 'default', 'value' => null],
            [['status'], 'default', 'value' => 1],
            [['c_name'], 'required'],
            [['status'], 'integer'],
            [['c_name'], 'string', 'max' => 200],
            [['seourl'], 'string', 'max' => 50],
            [['c_name'], 'unique', 'message' => 'This category name has already been taken.'],
            [['seourl'], 'unique', 'message' => 'This SEO URL has already been taken.'],

           
            // ['c_name', 'validateUniqueCName', 'on' => ['create', 'update']], 
            // ['seourl', 'validateUniqueSeoUrl', 'on' => ['create', 'update']], 
            
        ];
    }

   
    public function attributeLabels()
    {
        return [
            'c_id' => 'C ID',
            'c_name' => 'Category Name',
            'seourl' => 'SEO URL',
            'status' => 'Status',
        ];
    }

   
    public function getProducts()
    {
        return $this->hasMany(Product::class, ['category_id' => 'c_id']);
    }

    
    // public function validateUniqueCName($attribute, $params)
    // {
    //     $query = self::find()->where([$attribute => $this->$attribute]);

    //     if (!$this->isNewRecord) {
    //         $query->andWhere(['<>', 'c_id', $this->c_id]);
    //     }

    //     if ($query->exists()) {
    //         $this->addError($attribute, 'This category name has already been taken.');
    //     }
    // }

    
    // public function validateUniqueSeoUrl($attribute, $params)
    // {
    //     $query = self::find()->where([$attribute => $this->$attribute]);

   
    //     if (!$this->isNewRecord) {
    //         $query->andWhere(['<>', 'c_id', $this->c_id]);
    //     }

    //     if ($query->exists()) {
    //         $this->addError($attribute, 'This SEO URL has already been taken.');
    //     }
    // }

  
}