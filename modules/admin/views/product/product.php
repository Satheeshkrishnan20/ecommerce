<?php
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;

// Check if search input has value (expand with more fields if needed)
$showSearch = !empty($model->c_name);
$searchInputId = Html::getInputId($model, 'c_name');
?>

<script>
$(document).ready(function () {
    // Always toggle search box
    $('#toggleBtn').click(function () {
        $('#helloText').slideToggle();
    });

    // Delete confirmation and reload
    $(document).on('click', '.btn-delete', function (e) {
        e.preventDefault();
        let id = $(this).data('id');
        let url = '/auth/admin/product/delete?id=' + id;

        if (confirm('Are you sure you want to delete?')) {
            $.pjax.reload({
                container: '#pjax-container',
                url: url,
                push: false,
                replace: false,
                timeout: 10000
            });
        }
    });

    // Image preview
    $(document).on('click', '.preview-trigger', function () {
        const imgUrl = $(this).closest('a').data('image');
        $('#previewImage').attr('src', imgUrl);
        $('#imagePreviewModal').modal('show');
    });
});
</script>

<!-- Header with buttons -->
<div class='d-flex justify-content-between align-items-center mb-3'>
    <div><h5><?= Html::encode("Manage Product") ?></h5></div>
    <div class='d-flex gap-2'>
        <button class='btn btn-primary' id="toggleBtn"><i class="bi bi-search"></i></button>
        <?= Html::a('+ Create Product', ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Back', ['default/dashboard'], ['class' => 'btn btn-dark']) ?>
    </div>
</div>

<!-- Search Box -->
<div id="helloText" style="<?= $showSearch ? '' : 'display: none;' ?>">
    <div class="d-flex justify-content-center mb-4">
        <?php $form = ActiveForm::begin([
            'method' => 'get',
            'options' => ['class' => 'container'],
        ]); ?>

        <div class="row justify-content-center align-items-end mb-3">
            <div class="col-md-4">
                <?= $form->field($model, 'c_name')
                    ->textInput([
                        'maxlength' => true,
                        'placeholder' => 'Enter Category Name',
                        'class' => 'form-control'
                    ])
                    ->label(false) ?>
            </div>

            <div class="col-auto">
                <div class="btn-group">
                    <?= Html::submitButton('Search', ['class' => 'btn btn-success']) ?>
                    <?= Html::a('Reset', ['product'], ['class' => 'btn btn-outline-secondary']) ?>
                </div>
            </div>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>

<!-- Grid View -->
<?php Pjax::begin(['id' => 'pjax-container']); ?>

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
            'template' => '{delete} {update} {view}',
            'buttons' => [
                'delete' => function ($url, $model) {
                    return Html::a('Delete', $url, [
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
