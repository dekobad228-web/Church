<?php

namespace App\Schedule;

class Calendar
{
    public static function register(): void
    {
        register_taxonomy('calendar', ['month-calendar'], [
            'labels' => [
                'name'          => 'Расписание',
                'singular_name' => 'Год',
                'add_new_item'  => 'Добавить год',
                'new_item_name' => 'Новый год',
                'menu_name'     => 'Год',
                'back_to_items' => '← Назад',
            ],
            'public'       => false,
            'show_ui'      => true,
            'hierarchical' => true,
        ]);

        register_post_type('month-calendar', [
            'labels' => [
                'name'               => 'Календарь',
                'singular_name'      => 'Месяц',
                'add_new'            => 'Добавить месяц',
                'add_new_item'       => 'Добавить месяц',
                'edit_item'          => 'Редактирование месяца',
                'not_found'          => 'Не найдено',
                'not_found_in_trash' => 'Не найдено в корзине',
            ],
            'menu_icon'  => 'dashicons-calendar-alt',
            'taxonomies' => ['calendar'],
            'public'     => false,
            'show_ui'    => true,
            'supports'   => ['title', 'custom-fields', 'page-attributes'],
        ]);
    }
}
