<?php

defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');
date_default_timezone_set('Asia/Kolkata');

// require __DIR__ . '/vendor/autoload.php';
// require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';

// $config = require __DIR__ . '/../config/web.php';

// (new yii\web\Application($config))->run();

// index.php (after moving to root)

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/config/web.php';

(new yii\web\Application($config))->run();


?>