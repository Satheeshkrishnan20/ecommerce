<?php
// mail/account_created_html.php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user app\models\User */ // Your User model instance
/* @var $rawPassword string */ // The plain-text password passed from controller
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=<?= Yii::$app->charset ?>" />
    <title>Account Created Successfully</title>
    <style type="text/css">
        /* Basic inline styles for email client compatibility */
        body { font-family: Arial, sans-serif; font-size: 14px; color: #333; line-height: 1.6; margin: 0; padding: 0; background-color: #f4f4f4; }
        .container { max-width: 600px; margin: 20px auto; background-color: #fff; padding: 30px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .header { text-align: center; padding-bottom: 20px; border-bottom: 1px solid #eee; }
        .header h1 { color: #0056b3; margin: 0; }
        .content { padding-top: 20px; }
        .button-container { text-align: center; margin-top: 25px; }
        .button {
            display: inline-block;
            background-color: #007bff;
            color: #ffffff;
            padding: 12px 25px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            font-size: 16px;
        }
        .footer { text-align: center; margin-top: 30px; font-size: 12px; color: #777; border-top: 1px solid #eee; padding-top: 20px; }
        .credentials {
            background-color: #e9ecef;
            padding: 15px;
            border-radius: 5px;
            margin-top: 20px;
            text-align: center;
        }
        .credentials b { display: block; margin-bottom: 5px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Welcome to Amazon!</h1>
        </div>
        <div class="content">
            <p>Dear <?= Html::encode($user->username) ?>,</p>
            <p>Your Amazon account has been successfully created. We're excited to have you join our community!</p>

            <div class="credentials">
                <p>Your login details are:</p>
                <b>Username: <?= Html::encode($user->username) ?></b>
                <b>Password: <?= Html::encode($rawPassword) ?></b>
            </div>

            <p>You can now log in and start exploring all the features.</p>

            <div class="button-container">
                <a href="<?= Yii::$app->urlManager->createAbsoluteUrl(['site/login']) ?>" class="button">Login to your Account</a>
            </div>

            <p>If you have any questions, feel free to contact our support team.</p>
        </div>
        <div class="footer">
            <p>&copy; <?= date('Y') ?> Amazon. All rights reserved.</p>
        </div>
    </div>
</body>
</html>