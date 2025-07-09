<?php

namespace app\modules\admin;
use yii;

/**
 * admin module definition class
 */
class module extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\admin\controllers';

    public $defaultRoute='default/login';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        Yii::$app->layoutPath = Yii::getAlias('@app/modules/admin/views/layouts');
        Yii::$app->layout='admin';
        // custom initialization code goes here
    }
}
