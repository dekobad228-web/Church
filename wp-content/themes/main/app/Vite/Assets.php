<?php
namespace App\Vite;

class Assets {

    public static function init() {
        add_action('wp_head', [self::class, 'injectViteClient'], 1);
        add_action('wp_enqueue_scripts', [self::class, 'enqueue']);
    }

    public static function injectViteClient() {

        if (!Vite::isDev()) return;

        $entry = 'src/app.js';

        echo '<script type="module" src="' . Vite::devServer() . '/@vite/client"></script>';
        echo '<script type="module" src="' . Vite::devServer() . '/' . $entry . '"></script>';
    }

    public static function enqueue() {

        if (Vite::isDev()) return;

        $entry = 'src/app.js';

        wp_enqueue_script(
            'app',
            Vite::asset($entry),
            [],
            null,
            true
        );

        foreach (Vite::css($entry) as $css) {
            wp_enqueue_style(
                'app-css-' . md5($css),
                get_template_directory_uri() . '/dist/' . $css,
                [],
                null
            );
        }
    }
}