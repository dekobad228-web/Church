<?php

namespace App\Entity;

class Notes
{
    public static function register(): void
    {
        register_post_type('notes', [
            'labels' => [
                'name' => 'Записки',
                'menu_name' => 'Записки',
            ],
            'supports' => ['custom-fields', 'title', 'editor'],
            'public' => false,
            'show_ui' => true,
            'publicly_queryable' => false,
            'exclude_from_search' => true,
            'show_in_nav_menus' => false,
            'menu_icon' => 'dashicons-media-text'
        ]);
    }
}
