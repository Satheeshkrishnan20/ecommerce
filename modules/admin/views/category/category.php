<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
?>


<div class='d-flex justify-content-between align-items-center mb-3'>
    <div><h5>Manage Products</h5></div>
    <div class='d-flex gap-2'>
      
        

        <?= Html::a('+ Create Category', ['createcategory'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Back', ['default/dashboard'], ['class' => 'btn btn-dark']) ?>
    </div>
</div>


<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        'c_id',
        'c_name',

        [
            'class' => 'yii\grid\ActionColumn',
            'header' => 'Actions',
            'headerOptions' => ['style' => 'width:80px'], 
            'template' => '{update} {delete}', 
             'buttons' => [
        'update' => function ($url, $model) {
            return Html::a('<i class="bi bi-pencil"></i>', ['category/update', 'id' => $model->c_id], ['title' => 'Update']);
        },
        'delete' => function ($url, $model) {
            return Html::a('<i class="bi bi-trash"></i>', ['category/delete', 'id' => $model->c_id], [
                'data-method' => 'post',
                'data-confirm' => 'Are you sure you want to delete this item?',
            ]);
        },
    ]
            
        ],
    ],
]); ?>







