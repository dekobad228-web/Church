<?php

namespace App\Mailer;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mailer
{
    public static function send($subject, $data)
    {
        $mail = new PHPMailer(true);

        try {
            // SMTP
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'your@gmail.com';
            $mail->Password = 'app_password';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Encoding
            $mail->CharSet = 'UTF-8';

            // Sender
            $mail->setFrom('your@gmail.com', 'Site');
            $mail->addAddress($data);

            // Content
            $mail->isHTML(true);
            $mail->Subject = $subject;

            $body = '';

            $mail->Body = $body;
            $mail->AltBody = strip_tags($body);

            return $mail->send();

        } catch (Exception $e) {
            error_log('Mail error: ' . $mail->ErrorInfo);
            return false;
        }
    }
}
