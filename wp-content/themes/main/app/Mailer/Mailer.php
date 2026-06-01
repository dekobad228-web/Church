<?php

namespace App\Mailer;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mailer
{
    public static function send($subject, $data)
    {
        $result = ['status' => 'success'];

        $mail = new PHPMailer;
        $mail->isSMTP();
        $mail->Host    = 'smtp.gmail.com';
        $mail->Port    = 25;
        $mail->CharSet = 'UTF-8';
        $mail->isHTML(false);

        $mail->From     = $_SERVER['HTTP_HOST'] . '@' . gethostname();
        $mail->FromName = $_SERVER['HTTP_HOST'];

        $feedback_mail = get_field('info', 'option');
        if (!empty($feedback_mail['mails'])) {
            foreach (explode(',', $feedback_mail['mails']) as $address) {
                $mail->addAddress(trim($address));
            }
        }

        $mail->Subject = $subject;
        $mail->Body = self::mail_body($data);
        // $mail->AltBody = strip_tags($data);

        if (!$mail->send()) {
            $result['status']  = 'mail-not-sent';
            $result['message'] = $mail->ErrorInfo;
            error_log('PHPMailer Error: ' . $mail->ErrorInfo);
        }

        return $result;
    }

    public static function mail_body($data)
    {
        $body = '';
        if (!empty($data['name']))    $body .= "Имя: {$data['name']}\n";
        if (!empty($data['tel']))     $body .= "Телефон: {$data['tel']}\n";
        if (!empty($data['mail']))    $body .= "Email: {$data['mail']}\n";

        return $body;
    }
}
