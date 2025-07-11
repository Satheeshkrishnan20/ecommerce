<?php
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;

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

$showSearch = !empty($model->username);
$searchInputId = Html::getInputId($model, 'username');
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
        ],
    ],
    'tableOptions' => ['class' => 'table table-bordered table-hover', 'style' => 'width: 100%'],
]); ?>

<?php Pjax::end(); ?>

<script>
$(document).ready(function () {
    $('#toggleBtn').click(function () {
        $('#helloText').slideToggle();
    });

    $(document).on('click', '.btn-delete', function (e) {
        e.preventDefault();
        var id = $(this).data('id');
        var row = $(this).closest('tr');

        if (confirm('Are you sure you want to delete this user?')) {
            $.ajax({
                url: '/user/delete?id=' + id,
                type: 'POST',
                success: function () {
                    row.remove();
                },
                error: function () {
                    alert('Error deleting user. Please try again.');
                }
            });
        }
    });
});
</script>


