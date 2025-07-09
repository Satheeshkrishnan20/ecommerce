<?php
use yii\helpers\Html;
use yii\widgets\ListView;
use yii\helpers\Url;
?>

<style>
.cart-container {
    display: flex;
    gap: 30px;
    padding: 20px;
    align-items: flex-start;
}

/* LEFT SIDE - PRODUCT LIST */
.cart-product-card {
    border: 1px solid #ddd;
    border-radius: 10px;
    padding: 15px;
    margin-bottom: 15px;
    display: flex;
    gap: 20px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    background-color: #fff;
    align-items: flex-start;
    max-height: 180px;
    overflow: hidden;
}

.cart-product-image {
    width: 120px;
    height: 120px;
    object-fit: cover;
    border-radius: 8px;
}

.cart-product-details {
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.cart-product-name {
    font-size: 1.1rem;
    font-weight: bold;
    margin-bottom: 4px;
}

.cart-product-description {
    font-size: 0.85rem;
    color: #555;
    margin: 2px 0;
    line-height: 1.2;
    max-height: 2.4em;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.cart-product-price {
    font-size: 1rem;
    font-weight: 600;
    color: green;
    margin-top: 4px;
}

.cart-action-buttons {
    margin-top: auto;
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.cart-btn-secondary,
.cart-btn-danger {
    padding: 4px 10px;
    font-size: 0.85rem;
    border: none;
    border-radius: 5px;
    color: #fff;
    text-decoration: none;
    cursor: pointer;
}

.cart-btn-secondary {
    background-color: #ffc107;
}

.cart-btn-danger {
    background-color: #dc3545;
}

/* RIGHT SIDE - SUMMARY */
.cart-summary-table {
    width: 300px;
    background-color: #f9f9f9;
    padding: 15px;
    border-radius: 10px;
    flex-shrink: 0;
}

.cart-summary-table table {
    width: 100%;
    border-collapse: collapse;
}

.cart-summary-table th,
.cart-summary-table td {
    padding: 10px;
    border-bottom: 1px solid #ccc;
    text-align: left;
}

.cart-summary-table tfoot td {
    font-weight: bold;
}

.cart-checkout-btn {
    margin-top: 20px;
    background-color: #28a745;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    color: #fff;
    font-size: 1rem;
    cursor: pointer;
    display: block;
    text-align: center;
    width: 100%;
    text-decoration: none;
}
</style>

<div class="cart-container">

    <!-- LEFT: PRODUCT LIST -->
    <div style="flex: 1;">
        <?= ListView::widget([
            'dataProvider' => $dataProvider,
            'itemView' => function ($model) {
                $product = $model->product;

                if (!$product) {
                    return Html::tag('div', 'Product not found.', ['class' => 'cart-product-card', 'style' => 'color:red']);
                }

                $category = $product->category;
                $image = $product->product_image ? $product->product_image : 'default.png';
                $imageUrl = Url::to("@web/images/products/{$image}");

                return Html::tag('div',
                    Html::img($imageUrl, ['class' => 'cart-product-image']) .
                    Html::tag('div',
                        Html::tag('div', Html::encode($product->product_name), ['class' => 'cart-product-name']) .
                        Html::tag('div', Html::encode($product->product_description), ['class' => 'cart-product-description']) .
                        Html::tag('div', 'Category: ' . Html::encode($category->c_name ?? 'N/A'), ['class' => 'cart-product-description']) .
                        Html::tag('div', 'Price: ₹' . Html::encode($product->product_price), ['class' => 'cart-product-price']) .
                        Html::tag('div', 'Quantity: ' . Html::encode($model->quantity), ['class' => 'cart-product-description']) .
                        Html::tag('div',
                            Html::a('Remove', ['cart/remove', 'id' => $model->cart_id], ['class' => 'cart-btn-danger']) . ' ' .
                            Html::a('Add to Wishlist', ['wishlist/add', 'product_id' => $product->p_id], ['class' => 'cart-btn-secondary']),
                            ['class' => 'cart-action-buttons']
                        ),
                        ['class' => 'cart-product-details']
                    ),
                    ['class' => 'cart-product-card']
                );
            },
            'layout' => "{items}\n{pager}",
        ]) ?>
    </div>

    <!-- RIGHT: SUMMARY PANEL -->
    <div class="cart-summary-table">
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Qty</th>
                    <th>Unit</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php $total = 0; ?>
                <?php foreach ($dataProvider1->getModels() as $model): ?>
                    <?php
                        $product = $model->product;
                        if (!$product) continue;
                        $subtotal = $product->product_price * $model->quantity;
                        $total += $subtotal;
                    ?>
                    <tr>
                        <td><?= Html::encode($product->product_name) ?></td>
                        <td><?= Html::encode($model->quantity) ?></td>
                        <td>₹<?= Html::encode($product->product_price) ?></td>
                        <td>₹<?= Html::encode($subtotal) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3">Subtotal</td>
                    <td>₹<?= $total ?></td>
                </tr>
                <tr>
                    <td colspan="3">Grand Total</td>
                    <td>₹<?= $total ?></td>
                </tr>
            </tfoot>
        </table>

        <?= Html::a('Proceed to Checkout', ['checkout/index'], ['class' => 'cart-checkout-btn']) ?>
    </div>
</div>
