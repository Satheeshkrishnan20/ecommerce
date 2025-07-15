<?php
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\helpers\Url;


// $this->registerJsFile(Yii::getAlias('@web') . '/js/product.js', ['depends' => [\yii\web\JqueryAsset::class]]);



$user = Yii::$app->user->identity;
$usertype = $user?->usertype ?? null;

$template = '';
if ($usertype == 3 || $user?->hasPermission('update_product')) {
    $template .= '{update} ';
}
if ($usertype == 3 || $user?->hasPermission('delete_product')) {
    $template .= '{delete} ';
}
$template .= '{view}';
$template = trim($template);
?>

<div class='d-flex justify-content-between align-items-center mb-3'>
    <div><h5><?= Html::encode("Manage Product") ?></h5></div>
    <div class='d-flex gap-2'>
        <button class='btn btn-primary' id="toggleBtn"><i class="bi bi-search"></i></button>
        <?php if ($usertype == 3 || $user?->hasPermission('create_product')): ?>
            <?= Html::a('+ Create Product', ['create'], ['class' => 'btn btn-success']) ?>
        <?php endif; ?>
        <?= Html::a('Back', ['default/dashboard'], ['class' => 'btn btn-dark']) ?>
    </div>
</div>

<?php Pjax::begin([
    'id' => 'pjax-container',
    'enablePushState' => false,
    'timeout' => 10000
]); ?>

<!-- Search Form -->
<div id="helloText" style="display: <?= !empty($category) ? 'block' : 'none' ?>;">
    <div class="d-flex justify-content-center mb-4">
        <form id="category-form" method="post" action="<?= Url::to(['product']) ?>" class="container" data-pjax>
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
                        <?= Html::a('Reset', ['product'], ['class' => 'btn btn-outline-secondary']) ?>
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
        'p_id',
        'product_name',
        'product_price',
        'product_instock',
        [
            'attribute' => 'category_id',
            'label' => 'Category',
            'value' => fn($model) => $model->category->c_name ?? 'No Category',
        ],
        [
            'attribute' => 'status',
            'value' => fn($model) => $model->status ? 'Active' : 'Inactive',
        ],
        [
            'attribute' => 'product_image',
            'format' => 'raw',
            'value' => function ($model) {
                $url = Yii::getAlias('@web') . '/images/products/' . $model->product_image;
                return Html::a(
                    Html::img($url, ['width' => '50', 'class' => 'preview-trigger', 'style' => 'cursor:pointer']),
                    'javascript:void(0)',
                    ['data-image' => $url]
                );
            },
        ],
        [
            'class' => 'yii\grid\ActionColumn',
             'header' => 'Actions',
              'headerOptions' => ['class' => 'text-primary'],       // Bootstrap class to center header text
               
            'template' => $template,
            'buttons' => [
                'delete' => function ($url, $model) {
                    return Html::a('<i class="bi bi-trash text-primary"></i>', 'javascript:void(0);', [
                        'class' => 'btn-delete text-danger',
                        'data-id' => $model->p_id,
                        'data-pjax' => '0'
                    ]);
                }
            ]
        ],
    ],
]); ?>

<?php Pjax::end(); ?>

<!-- Image Preview Modal -->
<div class="modal fade" id="imagePreviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content p-2">
            <div class="modal-header">
                <h5 class="modal-title">Image Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img id="previewImage" src="" class="img-fluid rounded shadow">
            </div>
        </div>
    </div>
</div>


<script>
$(document).ready(function () {
    // Toggle search box
    $('#toggleBtn').click(function () {
        $('#helloText').slideToggle();
    });

    // PJAX submit manually for POST
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

    // Delete product via AJAX
    $(document).on('click', '.btn-delete', function (e) {
        e.preventDefault();
        let id = $(this).data('id');
        let url = '/auth/admin/product/delete?id=' + id;

        $.ajax({
            url: url,
            type: 'POST',
            dataType: 'json',
            

            success: function (res) {
                if (res.success) {
                    $.pjax.reload({ container: '#pjax-container', timeout: 10000 });
                    alert(res.message);
                } else {
                    alert(res.message || 'Delete failed. Please try again.');
                }
            },
            error: function () {
                alert('Server error. Please try again.');
            }
        });
    });

    // Image preview
    $(document).on('click', '.preview-trigger', function () {
        const imgUrl = $(this).closest('a').data('image');
        $('#previewImage').attr('src', imgUrl);
        $('#imagePreviewModal').modal('show');
    });
});
</script>
