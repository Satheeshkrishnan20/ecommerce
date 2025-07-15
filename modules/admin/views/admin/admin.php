<?php
use yii\helpers\Html;
use yii\grid\GridView;

$user = Yii::$app->user->identity;
$usertype = $user?->usertype ?? null;

$template = '';
if ($usertype == 3 || $user?->hasPermission('update_admin')) {
    $template .= '{update} ';
}
if ($usertype == 3 || $user?->hasPermission('delete_admin')) {
    $template .= '{delete} ';
}
$template .= '{view}';
$template = trim($template);
?>

<div class='d-flex justify-content-between align-items-center mb-3'>
    <div><h5><?= Html::encode('Manage Admin') ?></h5></div>
    <div class='d-flex gap-2'>
        <?php if ($usertype == 3 || $user?->hasPermission('create_admin')): ?>
            <?= Html::a('+ Create Admin', ['admin/create'], ['class' => 'btn btn-success']) ?>
        <?php endif; ?>
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
            'template' => $template,
            'header' => 'Actions',
              'headerOptions' => ['class' => 'text-primary'], 
            'buttons' => [
                'view' => function ($url, $model, $key) {
                    return Html::a('<i class="bi bi-bag-plus text-primary"></i>', $url, [
                        'class' => '',
                        'title' => 'View Details',
                    ]);
                },
            ]
        ],
    ],
]); ?>
