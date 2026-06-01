<?php

namespace App\Payment;

use App\Notes\Notes;

class Robokassa
{
    private const TEST = true;

    private const TEST_LOGIN      = 'test_template_land3';
    private const TEST_PASSWORD_1 = 'zTluXv402dfJ3Oit3LUh';
    private const TEST_PASSWORD_2 = 'Rm0AbLK90gh0O8pPBPqP';

    private const PROD_LOGIN      = 'test_template_land3';
    private const PROD_PASSWORD_1 = 'GynYSYqDc8cGQj74ww26';
    private const PROD_PASSWORD_2 = 'a727kpzZSh0uhjOgMj1z';

    public static function init()
    {
        add_action('wp_ajax_confirm_payment', [self::class, 'confirm']);
        add_action('wp_ajax_nopriv_confirm_payment', [self::class, 'confirm']);

        add_action('wp_ajax_fail_payment', [self::class, 'fail']);
        add_action('wp_ajax_nopriv_fail_payment', [self::class, 'fail']);
    }

    public static function create_payment($donation_amount, $meta = [])
    {
        $login     = self::get_login();
        $password1 = self::get_password_1();

        $post_id = Notes::create($donation_amount, $meta);

        $sum = number_format((float) $donation_amount, 2, '.', '');

        $signature = md5("{$login}:{$sum}:{$post_id}:{$password1}");

        $params = [
            'MerchantLogin'  => $login,
            'OutSum'         => $sum,
            'InvId'          => $post_id,
            'Description'    => 'Пожертвование',
            'SignatureValue' => $signature,
        ];

        if (self::TEST) {
            $params['IsTest'] = 1;
        }

        return [
            'id'   => $post_id,
            'link' => 'https://auth.robokassa.ru/Merchant/Index.aspx?' . http_build_query($params),
        ];
    }

    public static function confirm()
    {
        error_log('Robokassa confirm: ' . print_r($_REQUEST, true));

        if (
            empty($_REQUEST['OutSum']) ||
            empty($_REQUEST['InvId']) ||
            empty($_REQUEST['SignatureValue'])
        ) {
            status_header(400);
            echo 'bad request';
            wp_die();
        }

        $post_id = (int) $_REQUEST['InvId'];

        $out_sum = sanitize_text_field(wp_unslash($_REQUEST['OutSum']));
        $signature_from_robokassa = strtoupper(
            sanitize_text_field(wp_unslash($_REQUEST['SignatureValue']))
        );

        $password2 = self::get_password_2();

        $site_signature = strtoupper(md5("{$out_sum}:{$post_id}:{$password2}"));

        if ($site_signature !== $signature_from_robokassa) {
            error_log("Robokassa bad signature. Site: {$site_signature}, Robokassa: {$signature_from_robokassa}");

            status_header(403);
            echo 'bad sign';
            wp_die();
        }

        $post = get_post($post_id);

        if (!$post || $post->post_type !== 'notes') {
            status_header(404);
            echo 'not found';
            wp_die();
        }

        $payment_status = get_post_meta($post_id, 'payment_status', true);

        if ($payment_status !== 'paid') {
            Notes::mark_paid($post_id);
        }

        echo 'OK' . $post_id;
        wp_die();
    }

    public static function fail()
    {
        $post_id = !empty($_REQUEST['InvId']) ? (int) $_REQUEST['InvId'] : 0;

        if (!$post_id) {
            wp_safe_redirect(home_url('/'));
            exit;
        }

        $post = get_post($post_id);

        if ($post && $post->post_type === 'notes') {
            Notes::mark_failed($post_id);
        }

        wp_safe_redirect(home_url('/payment-fail/'));
        exit;
    }

    private static function get_login()
    {
        return self::TEST ? self::TEST_LOGIN : self::PROD_LOGIN;
    }

    private static function get_password_1()
    {
        return self::TEST ? self::TEST_PASSWORD_1 : self::PROD_PASSWORD_1;
    }

    private static function get_password_2()
    {
        return self::TEST ? self::TEST_PASSWORD_2 : self::PROD_PASSWORD_2;
    }
}