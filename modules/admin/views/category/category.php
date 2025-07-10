<?php
use yii\helpers\Html;
use yii\grid\GridView;

$rbac = Yii::$app->session->get('rbac', []);
$usertype = Yii::$app->session->get('usertype');

// Set template conditionally
$template = '';
if ($usertype == 3 || in_array('update_category', $rbac)) {
    $template .= '{update} ';
}
if ($usertype == 3 || in_array('delete_category', $rbac)) {
    $template .= '{delete}';
}
$template = trim($template); // Remove extra space
?>

<div class='d-flex justify-content-between align-items-center mb-3'>
    <div><h5>Manage Categories</h5></div>
    <div>
        <?php if ($usertype == 3 || in_array('create_category', $rbac)) : ?>
            <?= Html::a('Create Category', ['createcategory']) ?>
        <?php endif; ?>
        <?= Html::a('Back', ['default/dashboard']) ?>
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
            'template' => $template, // Uses default update/delete icons
        ],
    ],
]); ?>
