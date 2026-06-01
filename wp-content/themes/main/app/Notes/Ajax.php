<?php

namespace App\Notes;

use App\Payment\Robokassa;

class Ajax
{
    public static function init()
    {
        add_action('wp_ajax_notes', [self::class, 'send']);
        add_action('wp_ajax_nopriv_notes', [self::class, 'send']);
    }

    public static function send()
    {
        $errors = [];

        $raw_names = $_POST['names-array'] ?? [];
        $raw_names = is_array($raw_names) ? $raw_names : [];

        $names = [];

        foreach ($raw_names as $name) {
            $name = sanitize_text_field(wp_unslash($name));

            if ($name !== '') {
                $names[] = $name;
            }
        }

        $type = sanitize_text_field(wp_unslash($_POST['type'] ?? ''));

        $commemoration = sanitize_text_field(wp_unslash($_POST['commemoration'] ?? ''));

        $donation_amount = (float) str_replace(
            ',',
            '.',
            wp_unslash($_POST['donate'] ?? 0)
        );

        $personal_agree = !empty($_POST['checkbox']) || !empty($_POST['personal_agree']);

        if (empty($names)) {
            $errors['names-array[]'] = ['Должно быть указано хотя бы одно имя'];
        }

        if ($type === '') {
            $errors['type'] = ['Не указан тип поминовения'];
        }

        if ($commemoration === '') {
            $errors['commemoration'] = ['Не указано поминовение'];
        }

        if ($donation_amount <= 0) {
            $errors['donate'] = ['Некорректная сумма пожертвования'];
        }

        if (!$personal_agree) {
            $errors['checkbox'] = ['Необходимо согласие на обработку персональных данных'];
        }

        if (!empty($errors)) {
            status_header(422);

            wp_send_json([
                'status' => 'not-valid',
                'errors' => $errors,
            ]);
        }

        $payment = Robokassa::create_payment($donation_amount, [
            'names'         => implode(', ', $names),
            'type'          => $type,
            'commemoration' => $commemoration,
            'full_type'     => trim($type . ' - ' . $commemoration),
        ]);

        header('HX-Redirect: ' . $payment['link']);

        wp_send_json([
            'status'   => 'success',
            'pay_link' => $payment['link'],
            'id'       => $payment['id'],
        ]);
    }
}