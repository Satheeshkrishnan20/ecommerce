<?php
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;

// RBAC & User Type
$rbac = Yii::$app->session->get('rbac', []);
$usertype = Yii::$app->session->get('usertype');

// Set template based on RBAC
$template = '';
if ($usertype == 3 || in_array('update_customer', $rbac)) {
    $template .= '{update} ';
}
if ($usertype == 3 || in_array('delete_customer', $rbac)) {
    $template .= '{delete}';
}
$template = trim($template);

// Search show/hide logic
$showSearch = !empty($model->username);
$searchInputId = Html::getInputId($model, 'username');
?>

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

<!-- Header -->
<div class='d-flex justify-content-between align-items-center mb-3'>
    <div><h5>User Manager</h5></div>
    <div class='d-flex gap-2'>
        <?php if ($usertype == 3 || in_array('create_customer', $rbac)): ?>
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
            'buttons' => [
                'delete' => function ($url, $model) {
                    return Html::a('Delete', $url, [
                        'class' => 'btn-delete text-danger',
                        'data-id' => $model->id,
                        'data-pjax' => '0'
                    ]);
                },
            ]
        ],
    ],
    'tableOptions' => ['class' => 'table table-bordered table-hover', 'style' => 'width: 100%'],
]); ?>

<?php Pjax::end(); ?>
