<?php
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

$this->title = 'Admin Login';
?>

<div class="d-flex justify-content-center align-items-center">
    <div class="card shadow p-4" style="width: 100%; max-width: 400px;">
        <h4 class="text-center mb-4"><?= $model->isNewRecord ? 'Create Admin' : 'Update Admin' ?></h4>

       

        <?php $form = ActiveForm::begin([
            'id' => 'login-form',
            'method' => 'post',
            'fieldConfig' => [
                'template' => "{input}\n{error}",
            ],
        ]); ?>

        <div class="mb-3">
            <?= $form->field($model, 'username')->textInput([
                'autofocus' => true,
                'placeholder' => 'Username',
                'class' => 'form-control'
            ]) ?>
        </div>

        <div class="mb-3">
            <?= $form->field($model, 'password')->passwordInput([
                'placeholder' => 'Password',
                'class' => 'form-control'
            ]) ?>
        </div>

        <!-- ✅ Buttons -->
        <div class="d-flex justify-content-between mt-3">
            <?= Html::submitButton('Save', ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Back', ['admin/admin'], ['class' => 'btn btn-secondary']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
