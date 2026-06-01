<?php

namespace App\Donate;

use App\Payment\Robokassa;

class DonateAjax
{
    public static function init()
    {
        add_action('wp_ajax_donate', [self::class, 'send']);
        add_action('wp_ajax_nopriv_donate', [self::class, 'send']);
    }

    public static function send()
    {
        $donation_amount = (float) str_replace(
            ',',
            '.',
            wp_unslash($_POST['donation_amount'] ?? 0)
        );

        if ($donation_amount <= 0) {
            status_header(422);

            wp_send_json([
                'status' => 'not-valid',
                'errors' => [
                    'donation_amount' => ['Введите сумму пожертвования'],
                ],
            ]);
        }

        $payment = Robokassa::create_payment($donation_amount, [
            'type'      => 'Пожертвование',
            'full_type' => 'Добровольное пожертвование',
        ]);

        header('HX-Redirect: ' . $payment['link']);

        wp_send_json([
            'status'   => 'success',
            'pay_link' => $payment['link'],
            'id'       => $payment['id'],
        ]);
    }
}