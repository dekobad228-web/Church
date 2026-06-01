<?php

namespace App\Notes;

class Notes
{
    public static function create($donation_amount, $add_meta = [])
    {
        $notes = get_posts([
            'post_type'   => 'notes',
            'post_status' => ['publish', 'draft'],
            'numberposts' => -1,
            'fields'      => 'ids',
        ]);

        $count = count($notes) + 1;

        $names         = $add_meta['names'] ?? '';
        $type          = $add_meta['type'] ?? '';
        $commemoration = $add_meta['commemoration'] ?? '';
        $full_type     = $add_meta['full_type'] ?? '';

        $post_content = '';

        if ($type) {
            $post_content .= "Тип поминовения: {$type}\n";
        }

        if ($commemoration) {
            $post_content .= "Поминовение: {$commemoration}\n\n";
        }

        if ($names) {
            $post_content .= "Имена:\n";

            $names_array = array_map('trim', explode(',', $names));

            foreach ($names_array as $i => $name) {
                if ($name !== '') {
                    $post_content .= ($i + 1) . ". {$name}\n";
                }
            }

            $post_content .= "\n";
        }

        $post_content .= "Сумма пожертвования: {$donation_amount} руб.";

        $post_id = wp_insert_post([
            'post_type'    => 'notes',
            'post_status'  => 'draft',
            'post_title'   => "Записка №{$count}",
            'post_content' => $post_content,
        ], true);

        if (is_wp_error($post_id)) {
            error_log('Notes create error: ' . $post_id->get_error_message());

            status_header(500);

            wp_send_json([
                'status'  => 'error',
                'message' => 'Не удалось создать записку',
            ]);
        }

        $meta = [
            'donation_amount' => $donation_amount,
            'payment_status'  => 'waiting',
            'names'           => $names,
            'type'            => $type,
            'commemoration'   => $commemoration,
            'full_type'       => $full_type,
        ];

        foreach ($meta as $key => $value) {
            update_post_meta($post_id, $key, $value);
        }

        return $post_id;
    }

    public static function mark_paid($post_id)
    {
        update_post_meta($post_id, 'payment_status', 'paid');

        wp_update_post([
            'ID'          => $post_id,
            'post_status' => 'publish',
            'post_title'  => preg_replace(
                '/ — (оплачено|не оплачено)$/u',
                '',
                get_the_title($post_id)
            ) . ' — оплачено',
        ]);
    }

    public static function mark_failed($post_id)
    {
        update_post_meta($post_id, 'payment_status', 'failed');

        wp_update_post([
            'ID'          => $post_id,
            'post_status' => 'draft',
            'post_title'  => preg_replace(
                '/ — (оплачено|не оплачено)$/u',
                '',
                get_the_title($post_id)
            ) . ' — не оплачено',
        ]);
    }
}