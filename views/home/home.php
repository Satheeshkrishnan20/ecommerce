<?php

use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\widgets\ListView;

$this->title = 'Shop';
?>

<div class="container-fluid">
    <div class="row">

        <!-- ✅ Left: Category Sidebar -->
        <aside class="col-md-3 mb-4">
            <div class="category-sidebar p-3 bg-light rounded">
                <h5 class="mb-3">Filter By Categories</h5>

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
            <?= ListView::widget([
                'dataProvider' => $dataProvider,
                'itemView' => 'card',
                'layout' => "{items}\n<div class='mt-4'>{pager}</div>",
                'itemOptions' => ['class' => 'col-lg-4 col-md-6 mb-4'],
                'options' => ['class' => 'row gx-4 gy-4'], 
            ]) ?>
            <?php Pjax::end(); ?>
        </main>

    </div>
</div>

<!-- ✅ JavaScript -->
<script>
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
</script>
