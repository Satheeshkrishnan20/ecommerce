<?php
/**
 * @var yii\web\View $this
 * @var app\models\OtpVerificationForm $model
 */

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Alert;

$this->title = 'Verify OTP';
$this->params['breadcrumbs'][] = $this->title;

$email = Yii::$app->session->get('user_otp_verification_email');
?>

<div class="site-verify-otp">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8 col-sm-10">
            <div class="card shadow-sm">
                <div class="card-header text-center bg-primary text-white">
                    <h1 class="card-title h3 mb-0"><?= Html::encode($this->title) ?></h1>
                </div>
                <div class="card-body">
                  

                    <?php if (Yii::$app->session->hasFlash('error')): ?>
                        <?= Alert::widget([
                            'options' => ['class' => 'alert-danger'],
                            'body' => Yii::$app->session->getFlash('error'),
                        ]) ?>
                    <?php endif; ?>

                    <p class="text-center mb-4">
                        Please enter the 4-digit OTP sent to your email address:
                        <strong><?= Html::encode($email) ?></strong>
                    </p>

                    <?php $form = ActiveForm::begin([
                           'id' => 'otp-form',
                            'enableClientValidation' => false,
                            'enableAjaxValidation' => false,
                            'validateOnSubmit' => false,
                    ]); ?>

                    <?= Html::hiddenInput('User[otpkey]', $model->otpkey) ?>

                    <div class="form-group mb-4">
                        <label class="form-label text-center d-block">Enter OTP</label>
                        <div class="d-flex justify-content-center otp-input-container">
                            <?= $form->field($model, 'otp1', [
                                'template' => '{input}{error}',
                                'options' => ['class' => ''],
                            ])->textInput([
                                'class' => 'form-control otp-input text-center me-2',
                                'maxlength' => 1,
                                'pattern' => '[0-9]',
                                'inputmode' => 'numeric',
                                'autocomplete' => 'one-time-code',
                                'required' => true,
                                'tabindex' => 1,
                            ])->label(false) ?>

                            <?= $form->field($model, 'otp2', [
                                'template' => '{input}{error}',
                                'options' => ['class' => ''],
                            ])->textInput([
                                'class' => 'form-control otp-input text-center me-2',
                                'maxlength' => 1,
                                'pattern' => '[0-9]',
                                'inputmode' => 'numeric',
                                'required' => true,
                                'tabindex' => 2,
                            ])->label(false) ?>

                            <?= $form->field($model, 'otp3', [
                                'template' => '{input}{error}',
                                'options' => ['class' => ''],
                            ])->textInput([
                                'class' => 'form-control otp-input text-center me-2',
                                'maxlength' => 1,
                                'pattern' => '[0-9]',
                                'inputmode' => 'numeric',
                                'required' => true,
                                'tabindex' => 3,
                            ])->label(false) ?>

                            <?= $form->field($model, 'otp4', [
                                'template' => '{input}{error}',
                                'options' => ['class' => ''],
                            ])->textInput([
                                'class' => 'form-control otp-input text-center',
                                'maxlength' => 1,
                                'pattern' => '[0-9]',
                                'inputmode' => 'numeric',
                                'required' => true,
                                'tabindex' => 4,
                            ])->label(false) ?>
                        </div>
                    </div>

                    <div class="d-grid gap-2 mb-3">
                        <?= Html::submitButton('Verify OTP', ['class' => 'btn btn-primary btn-lg']) ?>
                    </div>

                   
                    
                    
                    <?php ActiveForm::end(); ?>
                    <div class="d-flex justify-content-center align-items-center gap-3 mt-3 otp-resend-wrapper">
                    <div class="text-muted small fw-semibold" id="timer-text">
                        You can resend the OTP <span id="now">in</span> <span id="count">0s</span>
                    </div>
                    <a href="javascript:void(0);"
                    id="resend-otp-btn"
                    class="btn btn-info text-white resend-btn-disabled">
                        Resend OTP
                    </a>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$this->registerCss("
    .otp-input-container .otp-input {
        width: 50px;
        height: 50px;
        font-size: 1.5rem;
        font-weight: bold;
        text-align: center;
        border: 1px solid #ced4da;
        border-radius: .25rem;
        -moz-appearance: textfield;
    }
    .otp-input-container .otp-input:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, .25);
    }
    .otp-input-container .otp-input::-webkit-outer-spin-button,
    .otp-input-container .otp-input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    .otp-input-container .help-block-error {
        position: absolute;
        width: 100%;
        left: 0;
        top: 100%;
        font-size: 0.875em;
        color: #dc3545;
        text-align: center;
    }
");
?>

<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function () {
        const otpInputs = document.querySelectorAll('.otp-input');

        otpInputs.forEach((input, index) => {
            input.addEventListener('input', function () {
                if (this.value.length === 1 && index < otpInputs.length - 1) {
                    otpInputs[index + 1].focus();
                }
            });

            input.addEventListener('keydown', function (event) {
                const isBackspace = event.key === 'Backspace';
                const isDelete = event.key === 'Delete';
                const isArrowKey = event.key === 'ArrowLeft' || event.key === 'ArrowRight';
                const isTab = event.key === 'Tab';

                if (isTab || isDelete || isArrowKey) return;

                if (isBackspace) {
                    if (this.value.length === 0 && index > 0) {
                        event.preventDefault();
                        otpInputs[index - 1].focus();
                    }
                    return;
                }

                if (!/^[0-9]$/.test(event.key)) {
                    event.preventDefault();
                }
            });

            input.addEventListener('paste', function (event) {
                event.preventDefault();
                const pasteData = event.clipboardData.getData('text').trim();
                if (pasteData.length === otpInputs.length && /^\d+$/.test(pasteData)) {
                    otpInputs.forEach((otpInput, i) => {
                        otpInput.value = pasteData[i];
                    });
                    otpInputs[otpInputs.length - 1].focus();
                    otpInputs[otpInputs.length - 1].dispatchEvent(new Event('input'));
                } else if (pasteData.length > 0 && /^\d$/.test(pasteData.charAt(0))) {
                    this.value = pasteData.charAt(0);
                    this.dispatchEvent(new Event('input'));
                }
            });
        });

        if (otpInputs.length > 0) {
            otpInputs[0].focus();
        }

        document.getElementById('otp-form').addEventListener('submit', function (event) {
            const combinedOtp = Array.from(otpInputs).map(input => input.value).join('');
            // You can log or process combined OTP here if needed
            // console.log('Combined OTP:', combinedOtp);
        });
    });
</script>



<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> 

<script>
$(document).ready(function () {
    const $resendBtn = $('#resend-otp-btn');
    const $countLabel = $('#count');
    const $nowLabel = $('#now');
    const $otpInputs = $('.otp-input');

    let timer = null;
    let countdown = 20;

    function startCountdown() {
        $resendBtn.prop('disabled', true).css('pointer-events', 'none');
        $nowLabel.text('in');
        $countLabel.text(countdown + 's').show();

        timer = setInterval(() => {
            countdown--;
            $countLabel.text(countdown + 's');

            if (countdown <= 0) {
                clearInterval(timer);
                $resendBtn.prop('disabled', false).css('pointer-events', 'auto');
                $countLabel.text('');
                $nowLabel.text('');
                countdown = 20; // reset for next click
            }
        }, 1000);
    }

    // Initial countdown start
    startCountdown();

    $resendBtn.on('click', function (e) {
        $('.otp-input').val('');
                $('.otp-input').first().focus();
        e.preventDefault();

        clearInterval(timer);
        countdown = 20;
        startCountdown();

        // Send the AJAX request to resend OTP
            $.ajax({
            url: '<?= \yii\helpers\Url::to(['home/resend']) ?>',
            type: 'POST',
            headers: {
                'X-CSRF-Token': '<?= Yii::$app->request->getCsrfToken() ?>'
            },
            success: function (res) {
                console.log('OTP resent successfully');
                $('.otp-input').val('');
                $('.otp-input').first().focus();
            },
            error: function (xhr) {
                console.log('Error resending OTP', xhr.responseText);
                // alert('Failed to resend OTP');
            }
});

    });
});
</script>



