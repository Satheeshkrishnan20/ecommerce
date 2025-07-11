<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
?>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">

            <div class="card shadow">
                <div class="card-body">

                    <h4 class="text-center mb-4"><?= $model->isNewRecord ? 'Create Category' : 'Update Category' ?></h4>
                    

                    <?php $form = ActiveForm::begin(); ?>

                    <?= $form->field($model, 'c_name')->textInput(['maxlength' => true, 'placeholder' => 'Enter category name']) ?>
                    <?= $form->field($model, 'seourl')->textInput(['maxlength' => true, 'placeholder' => 'Enter category name']) ?>
                    <br>

                    <div class="form-group text-center d-flex justify-content-between">
                        <?= Html::submitButton('Save', ['class' => 'btn btn-success w-25']) ?>
                        <?= Html::a('Cancel',Url::to(['category/category']),['class'=>'btn btn-primary']) ?>
                    </div>

                    <?php ActiveForm::end(); ?>

                </div>
            </div>

        </div>
    </div>
</div>
