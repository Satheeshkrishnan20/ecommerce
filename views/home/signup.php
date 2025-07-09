<?php
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\Html;
?>

<div class="container my-5">
    <div class="card shadow p-4">
        <h4 class="text-center mb-4">User Signup</h4>

        <?php $form = ActiveForm::begin([
             'options' => ['class' => 'needs-validation'],
    'fieldConfig' => [
        'errorOptions' => ['class' => 'text-danger'], 
    ],
        ]); ?>

        <div class="row">
           
            <div class="col-md-6">
                <?= $form->field($model, 'username') ?>
                <?= $form->field($model, 'fullname') ?>
                <?= $form->field($model, 'email') ?>
                <?= $form->field($model, 'phone')->input('number') ?>
                
                <?= $form->field($model, 'gender')->radioList([
                    'male' => 'Male',
                    'female' => 'Female',
                    'other' => 'Other'
                ]) ?>

                <?= $form->field($model, 'dob')->widget(DatePicker::class, [
                    'bsVersion' => '5',
                    'options' => ['placeholder' => 'Select birth date...'],
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'yyyy-mm-dd',
                        'todayHighlight' => true,
                    ],
                ]) ?>
            </div>

            <!-- Right Column -->
            <div class="col-md-6">
                <?= $form->field($model, 'address')->textarea(['rows' => 3]) ?>
                <?= $form->field($model, 'pincode')->input('number') ?>
                <?= $form->field($model, 'district') ?>

                <?= $form->field($model, 'state')->widget(Select2::class, [
                    'data' => [
                        'Tamil Nadu' => 'Tamil Nadu',
                        'Kerala' => 'Kerala',
                        'Bihar' => 'Bihar',
                    ],
                    'language' => 'en',
                    'options' => ['placeholder' => 'Select state...'],
                    'pluginOptions' => ['allowClear' => true],
                ]) ?>

                <?= $form->field($model, 'password')->passwordInput() ?>
                <?= $form->field($model, 'confirm_password')->passwordInput() ?>

                <div class="mt-4">
                    <?= Html::submitButton('Submit', ['class' => 'btn btn-primary w-100']) ?>
                </div>
            </div>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
