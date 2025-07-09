<?php

use yii\widgets\ListView;
use yii\helpers\Html;

?>

<h3 class="mb-4">🛍️ Product Catalog</h3>

<?= ListView::widget([
    'dataProvider' => $dataProvider,
    'itemOptions' => ['class' => 'card mb-4 shadow-sm'],
    'layout' => "{items}\n<div class='d-flex justify-content-center'>{pager}</div>",
    'itemView' => function ($model) {
        $imageUrl = Yii::getAlias('@web') . '/images/products/' . $model->product_image;

        return '
        <div class="row g-0">
            <div class="col-md-3 text-center p-3 border-end">
                ' . Html::img($imageUrl, [
                    'class' => 'img-fluid rounded',
                    'style' => 'max-height:150px; object-fit:cover;',
                    'alt' => $model->product_name
                ]) . '
            </div>
            <div class="col-md-9 p-3">
                <h5 class="card-title mb-2">' . Html::encode($model->product_name) . '</h5>
                <p class="mb-1"><strong>💰 Price:</strong> ₹' . $model->product_price . '</p>
                <p class="mb-1"><strong>📦 Stock:</strong> ' . $model->product_instock . '</p>
                <p class="mb-1"><strong>📂 Category:</strong> ' . ($model->category->c_name ?? 'N/A') . '</p>
                <p class="mb-2"><strong>⚙️ Status:</strong> ' . ($model->status ? '✅ Active' : '❌ Inactive') . '</p>
                
            </div>
        </div>
        ';
    },
]); ?>
