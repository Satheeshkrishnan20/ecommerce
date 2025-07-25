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
                        'template' => "{label}\n{input}\n{error}",
                        'labelOptions' => ['class' => 'form-label'],
                        'errorOptions' => ['class' => 'text-danger mt-1'],
                    ],
                ]); ?>

                <div class="row g-4">
                    <!-- Left Column -->
                    <div class="col-md-6">
                        <?= $form->field($model, 'username')
                            ->label('Username <span class="text-danger">*</span>', ['encode' => false])
                            ->textInput(['placeholder' => 'Enter Username']) ?>

                        <?= $form->field($model, 'fullname')
                            ->label('Full Name <span class="text-danger">*</span>', ['encode' => false])
                            ->textInput(['placeholder' => 'Enter Full Name']) ?>

                        <?= $form->field($model, 'email')
                            ->label('Email <span class="text-danger">*</span>', ['encode' => false])
                            ->input('email', ['placeholder' => 'Enter Email']) ?>

                        <?= $form->field($model, 'phone')
                            ->label('Phone Number <span class="text-danger">*</span>', ['encode' => false])
                            ->input('number', ['placeholder' => 'Enter Phone Number', 'id' => 'phone-input']) ?>

                        <?= $form->field($model, 'gender')
                            ->label('Gender <span class="text-danger">*</span>', ['encode' => false])
                            ->radioList([
                                'male' => 'Male',
                                'female' => 'Female',
                                'other' => 'Other'
                            ], ['class' => 'form-check']) ?>

                        <?= $form->field($model, 'dob')
                            ->label('Date of Birth <span class="text-danger">*</span>', ['encode' => false])
                            ->widget(DatePicker::class, [
                                'bsVersion' => '5',
                                'options' => ['placeholder' => 'Select Birth Date...'],
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
                            ->textarea(['rows' => 3, 'placeholder' => 'Enter Address']) ?>

                        <?= $form->field($model, 'pincode')
                            ->label('Pincode <span class="text-danger">*</span>', ['encode' => false])
                            ->input('number', ['placeholder' => 'Enter Pincode', 'id' => 'pincode-input']) ?>

                        <?= $form->field($model, 'district')
                            ->label('District <span class="text-danger">*</span>', ['encode' => false])
                            ->textInput(['placeholder' => 'Enter District']) ?>

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
                            ]) ?>

                        <?php if ($model->isNewRecord): ?>
                            <?= $form->field($model, 'password')
                                ->label('Password <span class="text-danger">*</span>', ['encode' => false])
                                ->passwordInput(['placeholder' => 'Enter Password']) ?>

                            <?= $form->field($model, 'confirm_password')
                                ->label('Confirm Password <span class="text-danger">*</span>', ['encode' => false])
                                ->passwordInput(['placeholder' => 'Confirm Password']) ?>
                        <?php endif; ?>

                        <div class="mt-4 d-flex justify-content-between">
                            <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
                            <?= Html::a('← Back', ['user'], ['class' => 'btn btn-outline-secondary']) ?>
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
