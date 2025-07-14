<?php
use yii\helpers\Html;
use yii\widgets\ListView;
use yii\helpers\Url;
?>



<div class="cart-container">

  
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
                            Html::a('Remove', ['cart/remove', 'id' => $model->cart_id], ['class' => 'cart-btn-danger']) ),
                            // Html::a('Add to Wishlist', ['wishlist/add', 'product_id' => $product->p_id], ['class' => 'cart-btn-secondary']),
                            // ['class' => 'cart-action-buttons']
                        
                        ['class' => 'cart-product-details']
                    ),
                    ['class' => 'cart-product-card']
                );
            },
            'layout' => "{items}\n{pager}",
        ]) ?>
    </div>

   
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
