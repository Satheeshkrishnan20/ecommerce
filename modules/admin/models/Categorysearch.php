<?php

namespace app\modules\admin\models;

use Yii;

/**
 * This is the model class for table "auth".
 *
 * @property int $id
 * @property string $username
 * @property string $password
 */
class Categorysearch extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'category';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['c_name','safe'],
           
        ];
    }

    /**
     * {@inheritdoc}
     */
    

}
