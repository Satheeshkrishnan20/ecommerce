<?php
namespace app\modules\admin\assets;

use yii\web\AssetBundle;

class AdminAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $js = [
        'js/product.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
        'yii\bootstrap5\BootstrapAsset',
    ];
}

?>
