<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

$user = Yii::$app->user->identity;
$usertype = $user?->usertype ?? null;

// Set template conditionally
$template = '';
if ($usertype == 3 || $user?->hasPermission('update_category')) {
    $template .= '{update} ';
}
if ($usertype == 3 || $user?->hasPermission('delete_category')) {
    $template .= '{delete}';
}
$template = trim($template); // Remove extra space
?>

<div class='d-flex justify-content-between align-items-center mb-3'>
    <div><h5>Manage Categories</h5></div>
    <div>
        <?php if ($usertype == 3 || $user?->hasPermission('create_category')) : ?>
            <?= Html::a('Create Category', ['createcategory'],['class'=> 'btn btn-success']) ?>
        <?php endif; ?>
        <?= Html::a('Back', ['default/dashboard'],['class'=> 'btn btn-dark']) ?>
    </div>
</div>

<?php Pjax::begin([
    'id'=>'pjax-container',
    'enablePushState'=>false,
    'timeout'=>10000
]);
?>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        'c_id',
        'c_name',
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => $template, // Uses default update/delete icons
            'buttons'=>[
                'delete'=>function($url,$model){
                    return Html::a('<i class="bi bi-trash text-primary"></i>','javascript:void(0);',[
                            'class'=>' btn-delete',
                            'data-id'=>$model->c_id,
                            'data-pjax'=>'0'
                    ]);
                }
            ]
        ],
    ],
]); ?>

<?php Pjax::end() ?>

<script>
   $(document).ready(function () {
    $(document).on('click', '.btn-delete', function (e) {
        e.preventDefault();
        let id = $(this).data('id');
        <?php $deleteUrl = \yii\helpers\Url::to(['category/delete']); ?>
        let url = '<?= $deleteUrl ?>?id=' + id;

        $.ajax({
            url: url, // ✅ Use comma not semicolon
            type: 'POST', // ✅ Use comma, and use string 'POST'
            dataType: 'json', // ✅ Use lowercase 'json'
            success: function (res) {
                if (res.success) {
                    $.pjax.reload({ container: '#pjax-container', timeout: 10000 }); // ✅ 'container' not 'cotainer'
                } else {
                    alert(res.message || 'Delete failed.');
                }
            },
            error: function () {
                alert('Server Error. Please try again.');
            }
        });
    });
});

</script>
