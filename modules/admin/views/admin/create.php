<?php
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

$this->title = 'Admin Login';
?>

<div class="d-flex justify-content-center align-items-center" >
    <div class="card shadow p-4" style="width: 100%; max-width: 400px;">
        <h2 class="text-center mb-4"><?= Html::encode('Create Admin') ?></h2>

        <?php if (Yii::$app->session->hasFlash('error')): ?>
            <div class="alert alert-danger text-center">
                <?= Yii::$app->session->getFlash('error') ?>
            </div>
        <?php endif; ?>

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

        <div class="d-grid">
            <?= Html::submitButton('Create', ['class' => 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
