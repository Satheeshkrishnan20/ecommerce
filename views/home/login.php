<?php
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

$this->title = 'Login';
?>

<div class="d-flex justify-content-center align-items-start" style="min-height: 100vh; padding-top: 40px;">
    <div class="card shadow-sm" style="width: 400px;">
        <div class="card-body p-4">
            <h3 class="card-title text-center mb-4"><?= Html::encode($this->title) ?></h3>

            <?php $form = ActiveForm::begin([
                'id' => 'login-form',
                 
            ]); ?>

            <?= $form->field($model, 'username', [
                'inputOptions' => [
                    'class' => 'form-control',
                    'placeholder' => 'Username',
                    'autofocus' => true,
                ],
                'template' => "{input}\n{error}"
            ])->label(false) ?>

            <?= $form->field($model, 'password', [
                'inputOptions' => [
                    'class' => 'form-control',
                    'placeholder' => 'Password',
                ],
                'template' => "{input}\n{error}"
            ])->passwordInput()->label(false) ?>

            <?= Html::submitButton('Login', ['class' => 'btn btn-primary w-100 mt-3']) ?>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
