<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\modules\admin\models\Category;
use yii\helpers\Url;
use kartik\select2\Select2;
use yii\bootstrap\BootstrapAsset;

?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">

            <div class="card shadow">
                <div class="card-body p-4" style="max-height: 85vh; overflow-y: auto;"> <!-- Limit height -->

                    <h5 class="text-center mb-3">
                        <?= $model->isNewRecord ? 'Create Product' : 'Update Product' ?>
                    </h5>

                    <?php $form = ActiveForm::begin([
                        'options' => ['enctype' => 'multipart/form-data'],
                    ]); ?>

                    <?= $form->field($model, 'product_name')->textInput(['maxlength' => true]) ?>

                    <?= $form->field($model, 'product_price')->textInput(['type' => 'number', 'step' => '0.01']) ?>

                    <?= $form->field($model, 'category_id')->widget(Select2::class, [
                        'bsVersion' => '5',
                        'data' => ArrayHelper::map(Category::find()->all(), 'c_id', 'c_name'),
                        'options' => ['placeholder' => 'Select a Category...'],
                        'pluginOptions' => ['allowClear' => true, 'theme' => 'default'],
                    ]); ?>

                    <?= $form->field($model, 'product_description')->textInput(['maxlength' => true]) ?>

                    <?= $form->field($model, 'product_instock')->textInput(['type' => 'number']) ?>
                    <br>

                    <?= $form->field($model, 'product_image')->fileInput() ?>

                    <div id="preview-wrapper" class="mb-3" style="display:none;">
                        <label>Preview:</label><br>
                        <a id="preview-link" href="#" target="_blank">
                            <img id="preview-img" src="" style="max-height: 100px; border: 1px solid #ccc; padding: 4px;">
                        </a>
                    </div>

                    <?php if (!$model->isNewRecord && $model->product_image): ?>
                        <p class="mb-3">
                            Previous Image:
                            <a href="<?= Url::to("@web/images/products/{$model->product_image}") ?>" target="_blank">
                                <?= Html::encode($model->product_image) ?>
                            </a>
                        </p>
                    <?php endif; ?>

                    <div class="row">
                        <div class="col"><?= $form->field($model, 'min_quantity')->textInput(['maxlength' => true]) ?></div>
                        <div class="col"><?= $form->field($model, 'max_quantity')->textInput(['maxlength' => true]) ?></div>
                    </div>

                    <div class="form-group text-center d-flex justify-content-between mt-3">
                        <?= Html::submitButton('Save', ['class' => 'btn btn-success w-25']) ?>
                        <?= Html::a('Cancel', Url::to(['product/product']), ['class' => 'btn btn-primary']) ?>
                    </div>

                    <?php ActiveForm::end(); ?>

                </div>
            </div>

        </div>
    </div>
</div>


<script>

    $(document).ready(function () {
    $('#product-product_image').on('change', function (e) {
        const file = this.files[0];
        if (file) {
            const url = URL.createObjectURL(file);
            $('#preview-img').attr('src', url);
            $('#preview-link').attr('href', url);
            $('#preview-wrapper').show();
        } else {
            $('#preview-wrapper').hide();
        }
    });
});

</script>



