<?php

namespace App\Mailer;

use App\Mailer\Mailer;

class Ajax
{
    public static function init()
    {
        add_action('wp_ajax_send_form', [self::class, 'send']);
        add_action('wp_ajax_nopriv_send_form', [self::class, 'send']);
    }

    public static function send()
    {
        $data = [
            'name' => $_POST['name'] ?? '',
            'mail' => $_POST['mail'] ?? '',
            'tel' => $_POST['tel'] ?? '',
        ];
        Mailer::send(
            'Новая заявка с сайта',
            $data,
        );

        wp_send_json_success();
    }
}
