<?php
use yii\helpers\Html;

return [
    ['class' => 'yii\grid\SerialColumn'],
    'username',
    'fullname',
    'email',
    'phone',
    'address',
    'gender',
    'dob',
    'pincode',
    'state',
    'district',
    [
        'class' => 'yii\grid\ActionColumn',
        'template' => '{delete}',
        'buttons' => [
            'delete' => function ($url, $model) {
                return Html::a(
                    '<i class="fas fa-trash"></i>',
                    ['delete', 'id' => $model->id],
                    [
                        'class' => 'btn btn-danger btn-sm btn-delete',
                        'data-id' => $model->id,
                        'data-method' => 'post',
                        'data-pjax' => 1,
                        'data-confirm' => 'Are you sure you want to delete this user?'
                    ]
                );
            }
        ]
    ],
];
