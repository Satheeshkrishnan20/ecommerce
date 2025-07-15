<?php
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

$user = Yii::$app->user->identity;
$usertype = $user?->usertype ?? null;

$template = '';
if ($usertype == 3 || $user?->hasPermission('update_customer')) {
    $template .= '{update} ';
}
if ($usertype == 3 || $user?->hasPermission('delete_customer')) {
    $template .= '{delete}';
}
$template = trim($template);


?>

<!-- Header -->
<div class='d-flex justify-content-between align-items-center mb-3'>
    <div><h5>User Manager</h5></div>
    <div class='d-flex gap-2'>
        <?php if ($usertype == 3 || $user?->hasPermission('create_customer')): ?>
            <?= Html::a('+ Create user', ['create'], ['class' => 'btn btn-success']) ?>
        <?php endif; ?>
        <?= Html::a('Back', ['default/dashboard'], ['class' => 'btn btn-dark']) ?>
    </div>
</div>

<!-- PJAX wrapper -->
<?php Pjax::begin(['id' => 'pjax-container']); ?>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
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
            'template' => $template,
            'header' => 'Actions',
              'headerOptions' => ['class' => 'text-primary'], 
            'buttons'=>[
                'delete'=>function($url,$model){
                    return Html::a('<i class="bi bi-trash text-primary"></i>', 'javascript:void(0)',[
                        'class'=>'btn-delete',
                        'data-id'=>$model->id,
                        'data-pjax'=>0
                    ]);
                }
            ]
        ],
    ],
    'tableOptions' => ['class' => 'table table-bordered table-hover', 'style' => 'width: 100%'],
]); ?>

<?php Pjax::end(); ?>

<script>
$(document).ready(function () {
    $(document).on('click', '.btn-delete', function (e) {
        e.preventDefault();
        var id = $(this).data('id');
        var url = '<?= \yii\helpers\Url::to(['user/delete']) ?>?id=' + id;

        if (confirm('Are you sure you want to delete this user?')) {
            $.ajax({
                url: url,
                type: 'POST',
                dataType: 'json',
                success: function (res) {
                    if (res.success) {
                        alert(res.message);
                        $.pjax.reload({ container: '#pjax-container', timeout: 10000 });
                    } else {
                        alert(res.message || 'Delete failed.');
                    }
                },
                error: function () {
                    alert('Server error. Please try again.');
                }
            });
        }
    });
});

</script>


