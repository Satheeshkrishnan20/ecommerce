<?php

namespace app\components\assets;

use yii\web\AssetBundle;
use yii\web\View;

class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web/web/';

    public $css = [
        // Add your CSS files if needed
        'css/site.css',
        'css/admin.css',
         'css/bootstrap.min.css',
         
    ];

    public $js = [
        'js/jquery.js', // Local jQuery file
        'js/site.js',
        'js/bootstrap.bundle.min.js',
    ];
  

    public $jsOptions = [
        'position' => View::POS_HEAD, // Load JS in <head>
    ];
}
