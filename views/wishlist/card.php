<?php
use yii\helpers\Html;

/** @var $model app\models\Wishlist */

$product = $model->product;
if ($product === null) return;

$productImage = $product->product_image;
$productName = $product->product_name;
$productDesc = $product->product_description;
$categoryName = $product->category->c_name ?? 'N/A';
$price = $product->product_price;
$productId = $product->p_id;
$wishlistId = $model->wishlist_id;
?>

<div class="product-card h-100 d-flex flex-column p-2">
    <div>
        <img src="<?= Yii::getAlias('@web/images/products/' . $productImage) ?>"
             alt="<?= Html::encode($productName) ?>"
             class="img-fluid"
             style="width: 100%; height: 180px; object-fit: cover;">
    </div>

    <div class="pt-2 d-flex flex-column flex-grow-1">
        <h6 class="mb-1"><?= Html::encode($productName) ?></h6>
        <div class="mb-1"><small>Category: <?= Html::encode($categoryName) ?></small></div>
        <div class="mb-1 text-success fw-bold">Price: ₹<?= Html::encode($price) ?></div>
        <div class="mb-2" style="font-size: 0.9rem; color: #555;"><?= Html::encode($productDesc) ?></div>

        <?= Html::beginForm(['home/add'], 'post', ['class' => 'mt-auto']) ?>
            <?= Html::hiddenInput('product_id', $productId) ?>
            <div class="d-flex align-items-center mb-2">
                <label class="me-2 mb-0">Quantity:</label>
                <div class="input-group input-group-sm" style="width: 110px;">
                    <button type="button" class="btn btn-outline-secondary minus-btn">−</button>
                    <input type="text" name="quantity" class="form-control text-center quantity-input" value="1" readonly>
                    <button type="button" class="btn btn-outline-secondary plus-btn">+</button>
                </div>
            </div>
            <?= Html::submitButton('Add to Cart', ['class' => 'btn btn-success btn-sm w-100']) ?>
        <?= Html::endForm() ?>

       <?= Html::a('Remove', ['wishlist/delete', 'id' => $wishlistId], [
            'class' => 'btn btn-danger btn-sm w-100 mt-2',
            'data-confirm' => 'Are you sure you want to remove this item from your wishlist?',
        ]) ?>
    </div>
</div>

<?php
$this->registerCss(<<<CSS
.product-card {
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    background-color: #fff;
    box-shadow: 0 4px 8px rgba(0,0,0,0.05);
    transition: 0.2s ease;
}

.product-card:hover {
    transform: translateY(-3px);
}
CSS);

$this->registerJs(<<<JS
$('.minus-btn').click(function() {
    var input = $(this).siblings('.quantity-input');
    var value = parseInt(input.val());
    if (value > 1) input.val(value - 1);
});

$('.plus-btn').click(function() {
    var input = $(this).siblings('.quantity-input');
    input.val(parseInt(input.val()) + 1);
});
JS);
?>
