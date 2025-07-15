<?php

use yii\helpers\Html;

$this->title = 'Error';
?>

<div class="site-error text-center">
    <h1><?= Html::encode($this->title) ?></h1>

    <h2 class="text-danger">
        <?= Html::encode($exception->statusCode ?? 'Error') ?> - <?= Html::encode($exception->getMessage()) ?>
    </h2>

    <p>
        Sorry, the page you are looking for doesn't exist or an error occurred.
    </p>

    <p>
        <?= Html::a('← Go Back Home', ['home/login'], ['class' => 'btn btn-primary']) ?>
    </p>
</div>
