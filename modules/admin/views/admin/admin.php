<?php
use yii\helpers\Html;
use yii\grid\GridView;
?>




<div class='d-flex justify-content-between align-items-center mb-3'>
    <div><h5><?= Html::encode('Manage Admin') ?></h5></div>
    <div class='d-flex gap-2'>
       
        <?= Html::a('+ Create Admin', ['admin/create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Back', ['default/dashboard'], ['class' => 'btn btn-dark']) ?>
    </div>
</div>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        'username',
        
      
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{delete}  {update} {view}',
           
        ],
    ],
]); ?>