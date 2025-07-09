<?php
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\Html;
?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10 col-sm-12">
            <div class="card shadow-lg border-0 p-5">
                <h4 class="text-center mb-4">User Signup</h4>

                <?php $form = ActiveForm::begin([
                    'options' => ['class' => 'needs-validation'],
                    'fieldConfig' => [
                        'errorOptions' => ['class' => 'text-danger'],
                    ],
                ]); ?>

                <div class="row g-4">
                    <!-- Left Column -->
                    <div class="col-md-6">
                        <?= $form->field($model, 'username')->textInput(['class' => 'form-control', 'placeholder' => 'Enter Username']) ?>
                        <?= $form->field($model, 'fullname')->textInput(['class' => 'form-control', 'placeholder' => 'Enter Full Name']) ?>
                        <?= $form->field($model, 'email')->input('email', ['class' => 'form-control', 'placeholder' => 'Enter Email']) ?>
                        <?= $form->field($model, 'phone')->input('number', ['class' => 'form-control', 'placeholder' => 'Enter Phone Number']) ?>

                        <?= $form->field($model, 'gender')->radioList([
                            'male' => 'Male',
                            'female' => 'Female',
                            'other' => 'Other'
                        ], ['class' => 'form-check form-check-inline']) ?>

                        <?= $form->field($model, 'dob')->widget(DatePicker::class, [
                            'bsVersion' => '5',
                            'options' => ['class' => 'form-control', 'placeholder' => 'Select Birth Date...'],
                            'pluginOptions' => [
                                'autoclose' => true,
                                'format' => 'yyyy-mm-dd',
                                'todayHighlight' => true,
                            ],
                        ]) ?>
                    </div>

                    <!-- Right Column -->
                    <div class="col-md-6">
                        <?= $form->field($model, 'address')->textarea(['class' => 'form-control', 'rows' => 3, 'placeholder' => 'Enter Address']) ?>
                        <?= $form->field($model, 'pincode')->input('number', ['class' => 'form-control', 'placeholder' => 'Enter Pincode']) ?>
                        <?= $form->field($model, 'district')->textInput(['class' => 'form-control', 'placeholder' => 'Enter District']) ?>

                        <?= $form->field($model, 'state')->widget(Select2::class, [
                            'data' => [
                                'Tamil Nadu' => 'Tamil Nadu',
                                'Kerala' => 'Kerala',
                                'Bihar' => 'Bihar',
                            ],
                            'language' => 'en',
                            'options' => ['placeholder' => 'Select State...'],
                            'pluginOptions' => ['allowClear' => true],
                            'class' => 'form-control'
                        ]) ?>

                        <?= $form->field($model, 'password')->passwordInput(['class' => 'form-control', 'placeholder' => 'Enter Password']) ?>
                        <?= $form->field($model, 'confirm_password')->passwordInput(['class' => 'form-control', 'placeholder' => 'Confirm Password']) ?>

                        <div class="mt-4 d-flex justify-content-between">
                            <!-- Submit Button on Left -->
                            <?= Html::submitButton('Submit', ['class' => 'btn btn-primary w-auto me-3']) ?>

                            <!-- Now the Back button is on the right -->
                            <div class="d-flex">
                                <?= Html::a('← Back', ['user'], ['class' => 'btn btn-outline-secondary w-auto']) ?>
                            </div>
                        </div>
                    </div>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
