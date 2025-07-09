<?php

use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\widgets\ListView;

$this->title = 'Shop';
?>

<div class="shop-container row gx-5 gy-4">

    <!-- ✅ Left: Category Sidebar -->
    <aside class="col-md-3">
        <div class="category-sidebar">
            <h5 class="mb-3">Categories</h5>

            <?php Pjax::begin(['id' => 'category-pjax', 'enablePushState' => false]); ?>
            <form id="category-filter-form">
                <?php foreach ($category as $item): ?>
                    <div class="form-check mb-2">
                        <input
                            type="checkbox"
                            name="seourl[]"
                            value="<?= Html::encode($item->seourl) ?>"
                            class="form-check-input category-checkbox"
                            id="cat_<?= $item->c_id ?>"
                            <?= in_array($item->seourl, $selectedSeoUrls) ? 'checked' : '' ?>
                        >
                        <label class="form-check-label" for="cat_<?= $item->c_id ?>">
                            <?= Html::encode($item->c_name) ?>
                        </label>
                    </div>
                <?php endforeach; ?>
            </form>
            <?php Pjax::end(); ?>
        </div>
    </aside>

    <!-- ✅ Right: Product Listing -->
    <main class="col-md-9">
        <?php Pjax::begin(['id' => 'product-pjax', 'enablePushState' => false]); ?>
        <div class="row gy-4" id="product-list">
            <?= ListView::widget([
                'dataProvider' => $dataProvider,
                'itemView' => 'card',
                'layout' => "{items}\n<div class='mt-4'>{pager}</div>",
                'itemOptions' => ['class' => 'col-lg-4 col-md-6 mb-4'],
                'options' => ['class' => 'row'],
            ]) ?>
        </div>
        <?php Pjax::end(); ?>
    </main>
</div>

<!-- ✅ CSS Styling -->
<style>
.shop-container {
    padding: 2rem;
}

.category-sidebar {
    background: #f9f9f9;
    padding: 1.5rem;
    border-radius: 8px;
    border: 1px solid #ddd;
    height: fit-content;
    position: sticky;
    top: 1rem;
}

.product-card {
    border: 1px solid #ddd;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    background-color: #fff;
    display: flex;
    flex-direction: column;
    height: 100%;
    overflow: hidden;
    position: relative;
    transition: transform 0.2s ease-in-out;
}

.product-card:hover {
    transform: translateY(-4px);
}

.ratio-box {
    width: 100%;
    height: 180px;
    overflow: hidden;
}

.ratio-box img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.wishlist-icon-wrapper {
    position: absolute;
    top: 10px;
    right: 10px;
    opacity: 0;
    transition: opacity 0.3s ease-in-out;
    z-index: 10;
}
.product-card:hover .wishlist-icon-wrapper {
    opacity: 1;
}

.wishlist-icon-circle {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background-color: #f1f1f1;
    border-radius: 50%;
    width: 36px;
    height: 36px;
    font-size: 1.2rem;
    color: #999;
    transition: background-color 0.3s, color 0.3s;
    text-decoration: none;
    border: none;
    cursor: pointer;
}
.wishlist-icon-circle:hover {
    background-color: #ffe6e6;
    color: #dc3545;
}

@media (max-width: 767px) {
    .shop-container {
        flex-direction: column;
    }

    .category-sidebar {
        width: 100%;
        margin-bottom: 1rem;
    }
}
</style>

<!-- ✅ JavaScript -->
<?php
$js = <<<JS
$(document).on('change', '.category-checkbox', function () {
    const form = $('#category-filter-form');
    const data = form.serialize();

    $.pjax.reload({
        container: '#product-pjax',
        url: window.location.pathname + '?' + data,
        push: false,
        replace: false,
        timeout: 10000
    });
});
JS;
$this->registerJs($js);
?>
