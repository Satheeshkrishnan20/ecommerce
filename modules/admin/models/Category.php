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

    public static function getActiveCategory(){
        return Category::find()
            ->innerJoinWith('products')
            ->where(['category.status' => 1])
            ->groupBy('category.c_id')
            ->all();
    }

   
    public function getProducts()
    {
        return $this->hasMany(Product::class, ['category_id' => 'c_id']);
    }

    public function getCategoryCount(){
         return self::find()->count();
    }

    public function searchByName($categoryName = null)
        {
            $query = self::find()->where(['status' => 1]);

            if (!empty($categoryName)) {
                $query->andWhere(['like', 'c_name', $categoryName]);
            }

            return new \yii\data\ActiveDataProvider([
                'query' => $query,
                'pagination' => ['pageSize' => 10],
                'sort' => ['defaultOrder' => ['c_id' => SORT_DESC]],
            ]);
        }

  
}