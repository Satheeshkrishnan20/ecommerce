<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\modules\admin\models\Category;
use yii\helpers\Url;
use kartik\select2\Select2;
use yii\bootstrap\BootstrapAsset;

?>

<div class="container ">
    <div class="row justify-content-center">
        <div class="col-md-6"> 

            <div class="card shadow">
                <div class="card-body">

                    <h3 class="text-center">Product Form</h3>

                    <?php $form = ActiveForm::begin([
                        'options' => ['enctype' => 'multipart/form-data'],
                    ]); ?>
                       
                    <?= $form->field($model, 'product_name')->textInput(['maxlength' => true]) ?>   
                 
                    <?= $form->field($model, 'product_price')->textInput(['type' => 'number', 'step' => '0.01']) ?>

                    
                   

                  <?= $form->field($model, 'category_id')->widget(Select2::class, [
                    'bsVersion' => '5', 
                    'data' => ArrayHelper::map(Category::find()->all(), 'c_id', 'c_name'),
                    'options' => ['placeholder' => 'Select a Category...'],
                    'pluginOptions' => [
                        'allowClear' => true,
                         'theme' => 'default',
                    ],
                ]); ?>
                                
                    <?= $form->field($model, 'product_description')->textInput(['maxlength' => true]) ?>
                
                    <?= $form->field($model, 'product_instock')->textInput(['type' => 'number']) ?>

                    
                   

                    <?= $form->field($model, 'product_image')->fileInput() ?>

                  
                    <?php if (!$model->isNewRecord && $model->product_image): ?>
                    <p>
                        Current Image: 
                        <a href="<?= Url::to("@web/images/products/{$model->product_image}") ?>" target="_blank">
                            <?= Html::encode($model->product_image) ?>
                        </a>
                    </p>
                <?php endif; ?>
                <br>
                      
                    <div class="form-group text-center d-flex justify-content-between">
                        <?= Html::submitButton('Save', ['class' => 'btn btn-success w-25']) ?>
                        <?= Html::a('Cancel',Url::to(['product/product']),['class'=>'btn btn-primary']) ?>
                    </div>


                    <?php ActiveForm::end(); ?>

                </div>
            </div>

        </div>
    </div>
</div>



