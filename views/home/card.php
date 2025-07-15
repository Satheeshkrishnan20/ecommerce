<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var $model app\modules\admin\models\Product */
?>

<div class="card h-100 product-card">
    <!-- Wishlist -->
    <div class="wishlist-icon-wrapper">
        <?php $form = ActiveForm::begin([
            'action' => ['wishlist/add'],
            'method' => 'post',
            'options' => ['class' => 'd-inline'],
        ]); ?>
        <?= Html::hiddenInput('product_id', $model->p_id) ?>
        <?= Html::submitButton('<i class="bi bi-heart"></i>', [
            'class' => 'wishlist-icon-circle',
            'title' => 'Add to Wishlist',
        ]) ?>
        <?php ActiveForm::end(); ?>
    </div>

    <!-- Image -->
    <div class="ratio-box">
        <?php if ($model->product_image): ?>
            <img src="<?= Yii::getAlias('@web') . '/images/products/' . $model->product_image ?>"
                 alt="<?= Html::encode($model->product_name) ?>">
        <?php else: ?>
            <img src="https://via.placeholder.com/260x180?text=No+Image">
        <?php endif; ?>
    </div>

    <!-- Content -->
    <div class="p-3 d-flex flex-column h-75">
        <h5 class="mb-1"><?= Html::encode($model->product_name) ?></h5>
        <p class="mb-1"><small>Category: <?= Html::encode($model->category->c_name ?? 'N/A') ?></small></p>
        <h6 class="text-success mb-2">Price: ₹<?= Html::encode(number_format($model->product_price, 2)) ?></h6>
        <p class="text-muted mb-0 small">
            <?= Html::encode(
                strlen($model->product_description) > 70 ?
                substr($model->product_description, 0, 70) . '...' :
                $model->product_description
            ) ?>
        </p>

        <!-- Add to Cart -->
        <div class="mt-auto">
            <?= Html::beginForm(['home/add'], 'post', ['class' => 'd-flex flex-column align-items-start']) ?>
                <?= Html::hiddenInput('product_id', $model->p_id) ?>
                <div class="d-flex align-items-center mb-2 w-100">
                    <label class="form-label mb-0 me-2">Qty:</label>
                    <div class="d-flex align-items-center flex-grow-1">
                        <button type="button" class="btn btn-sm btn-outline-secondary minus-btn">−</button>
                        <input type="text" name="quantity" class="form-control quantity-input mx-1 text-center" value="<?= $model->min_quantity?>"     min="<?= $model->min_quantity ?>"  max="<?= $model->max_quantity ?>" style="width: 35px;" readonly>
                        <button type="button" class="btn btn-sm btn-outline-secondary plus-btn">+</button>
                    </div>
                </div>
                <?= Html::submitButton('Add to Cart', ['class' => 'btn btn-success btn-sm w-100']) ?>
            <?= Html::endForm() ?>
        </div>
    </div>
</div>

