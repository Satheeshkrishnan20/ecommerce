<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;

$user = Yii::$app->user->identity;
$usertype = $user?->usertype ?? null;

$template = '';
if ($usertype == 3 || $user?->hasPermission('update_category')) {
    $template .= '{update} ';
}
if ($usertype == 3 || $user?->hasPermission('delete_category')) {
    $template .= '{delete}';
}
$template = trim($template);
?>

<div class='d-flex justify-content-between align-items-center mb-3'>
    <div><h5>Manage Categories</h5></div>
    <div class="d-flex gap-2">
        <button class='btn btn-primary' id="toggleBtn"><i class="bi bi-search"></i></button>
        <?php if ($usertype == 3 || $user?->hasPermission('create_category')): ?>
            <?= Html::a('Create Category', ['createcategory'], ['class'=> 'btn btn-success']) ?>
        <?php endif; ?>
        <?= Html::a('Back', ['default/dashboard'], ['class'=> 'btn btn-dark']) ?>
    </div>
</div>

<?php Pjax::begin([
    'id'=>'pjax-container',
    'enablePushState'=>false,
    'timeout'=>10000
]); ?>

<!-- Search Form -->
<div id="searchBox" style="display: <?= !empty($category) ? 'block' : 'none' ?>;">
    <div class="d-flex justify-content-center mb-4">
        <form id="category-form" method="post" action="<?= Url::to(['category']) ?>" class="container" data-pjax>
            <?= Html::hiddenInput(Yii::$app->request->csrfParam, Yii::$app->request->csrfToken) ?>
            <div class="row justify-content-center align-items-end mb-3">
                <div class="col-md-4">
                    <input
                        type="text"
                        name="category"
                        value="<?= Html::encode($category ?? '') ?>"
                        class="form-control"
                        placeholder="Enter Category Name"
                        maxlength="255"
                    />
                </div>
                <div class="col-auto">
                    <div class="btn-group">
                        <button type="submit" class="btn btn-success">Search</button>
                        <?= Html::a('Reset', ['category'], ['class' => 'btn btn-outline-secondary']) ?>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Grid View -->
<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        'c_id',
        'c_name',
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => $template,
            'buttons' => [
                'delete' => function($url, $model) {
                    return Html::a('<i class="bi bi-trash text-primary"></i>', 'javascript:void(0);', [
                        'class' => 'btn-deletee',
                        'data-id' => $model->c_id,
                        'data-pjax' => '0'
                    ]);
                }
            ]
        ],
    ],
]); ?>

<?php Pjax::end(); ?>

<script>
$(document).ready(function () {
    // Toggle search box
    $('#toggleBtn').click(function () {
        $('#searchBox').slideToggle();
    });

    // Pjax form submit
    $(document).on('submit', '#category-form', function(e) {
        e.preventDefault();
        $.pjax({
            type: 'POST',
            url: $(this).attr('action'),
            data: $(this).serialize(),
            container: '#pjax-container',
            push: false,
            replace: false,
            timeout: 10000
        });
    });

    // Delete via AJAX
    $(document).on('click', '.btn-deletee', function (e) {
        e.preventDefault();
        let id = $(this).data('id');
        let url = '<?= Url::to(['category/delete']) ?>?id=' + id;

        $.ajax({
            url: url,
            type: 'POST',
            dataType: 'json',
            success: function (res) {
                if (res.success) {
                    alert("Category Deleted Successfully");
                    $.pjax.reload({ container: '#pjax-container', timeout: 10000 });
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
