<?php
namespace app\components;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Yii;

class Helper
{
    public static function send($to, $subject, $body, $fromEmail = 'your_email@gmail.com', $fromName = 'Your App')
    {
        $mail = new PHPMailer(true);
        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'esecstudy@gmail.com';  
            $mail->Password   = 'plcz xnda swlw bbbw';     
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

    public static function set($key, $value)
    {
        Yii::$app->session->set($key, $value);
    }

 
    public static function get($key, $default = null)
    {
        return Yii::$app->session->get($key, $default);
    }

  
    public static function has($key)
    {
        return Yii::$app->session->has($key);
    }


    public static function remove($key)
    {
        Yii::$app->session->remove($key);
    }

  
    public static function destroy()
    {
        Yii::$app->session->destroy();
    }

  
    public static function setFlash($key, $message)
    {
        Yii::$app->session->setFlash($key, $message);
    }

   
    public static function getFlash($key)
    {
        return Yii::$app->session->getFlash($key);
    }





}
