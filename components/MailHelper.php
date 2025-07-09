<?php
namespace app\components;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class MailHelper
{
    public static function send($to, $subject, $body, $fromEmail = 'your_email@gmail.com', $fromName = 'Your App')
    {
        $mail = new PHPMailer(true);
        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'esecstudy@gmail.com';  // ✅ your Gmail
            $mail->Password   = 'plcz xnda swlw bbbw';     // ✅ App password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            // Recipients
            $mail->setFrom($fromEmail, $fromName);
            $mail->addAddress($to);

            // Content
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $body;

            $mail->send();
            return true;
        } catch (Exception $e) {
            \Yii::error("Mail Error: {$mail->ErrorInfo}", __METHOD__);
            return false;
        }
    }
}
