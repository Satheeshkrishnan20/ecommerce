<?php
use yii\widgets\ListView;
?>

<div class="container">
    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'itemView' => 'card',
        'layout' => "{items}\n<div class='mt-4'>{pager}</div>",
        'options' => ['class' => 'row'],
        'itemOptions' => ['class' => 'col-md-6 col-lg-4 col-xl-3 mb-4'], // Let ListView handle columns
    ]) ?>
</div>
