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
                <h4 class="text-center mb-4"><?= $model->isNewRecord ? 'Create User' : 'Update User' ?></h4>

                <?php $form = ActiveForm::begin([
                    'options' => ['class' => 'needs-validation'],
                    'fieldConfig' => [
                        'errorOptions' => ['class' => 'text-danger'],
                    ],
                ]); ?>

                <div class="row g-4">
                    <!-- Left Column -->
                    <div class="col-md-6">
                        <?= $form->field($model, 'username')
                            ->label('Username <span class="text-danger">*</span>', ['encode' => false])
                            ->textInput(['class' => 'form-control', 'placeholder' => 'Enter Username']) ?>

                        <?= $form->field($model, 'fullname')
                            ->label('Full Name <span class="text-danger">*</span>', ['encode' => false])
                            ->textInput(['class' => 'form-control', 'placeholder' => 'Enter Full Name']) ?>

                        <?= $form->field($model, 'email')
                            ->label('Email <span class="text-danger">*</span>', ['encode' => false])
                            ->input('email', ['class' => 'form-control', 'placeholder' => 'Enter Email']) ?>

                        <?= $form->field($model, 'phone')
                            ->label('Phone Number <span class="text-danger">*</span>', ['encode' => false])
                            ->input('number', ['class' => 'form-control', 'placeholder' => 'Enter Phone Number', 'id' => 'phone-input']) ?>

                        <?= $form->field($model, 'gender')
                            ->label('Gender <span class="text-danger">*</span>', ['encode' => false])
                            ->radioList([
                                'male' => 'Male',
                                'female' => 'Female',
                                'other' => 'Other'
                            ], ['class' => 'form-check form-check-inline']) ?>

                        <?= $form->field($model, 'dob')
                            ->label('Date of Birth <span class="text-danger">*</span>', ['encode' => false])
                            ->widget(DatePicker::class, [
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
                        <?= $form->field($model, 'address')
                            ->label('Address <span class="text-danger">*</span>', ['encode' => false])
                            ->textarea(['class' => 'form-control', 'rows' => 3, 'placeholder' => 'Enter Address']) ?>

                        <?= $form->field($model, 'pincode')
                            ->label('Pincode <span class="text-danger">*</span>', ['encode' => false])
                            ->input('number', ['class' => 'form-control', 'placeholder' => 'Enter Pincode', 'id' => 'pincode-input']) ?>

                        <?= $form->field($model, 'district')
                            ->label('District <span class="text-danger">*</span>', ['encode' => false])
                            ->textInput(['class' => 'form-control', 'placeholder' => 'Enter District']) ?>

                        <?= $form->field($model, 'state')
                            ->label('State <span class="text-danger">*</span>', ['encode' => false])
                            ->widget(Select2::class, [
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

                        <?= $form->field($model, 'password')
                            ->label('Password <span class="text-danger">*</span>', ['encode' => false])
                            ->passwordInput(['class' => 'form-control', 'placeholder' => 'Enter Password']) ?>

                        <?= $form->field($model, 'confirm_password')
                            ->label('Confirm Password <span class="text-danger">*</span>', ['encode' => false])
                            ->passwordInput(['class' => 'form-control', 'placeholder' => 'Confirm Password']) ?>

                        <div class="mt-4 d-flex justify-content-between">
                            <?= Html::submitButton('Submit', ['class' => 'btn btn-primary w-auto me-3']) ?>

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

<script>
$(document).ready(function () {
    $('#phone-input').on('input', function () {
        let value = $(this).val();
        if (value.length > 10) {
            $(this).val(value.slice(0, 10));
        }
    });

    $('#pincode-input').on('input', function () {
        let value = $(this).val();
        if (value.length > 6) {
            $(this).val(value.slice(0, 6));
        }
    });
});
</script>
