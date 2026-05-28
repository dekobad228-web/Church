<?php

namespace App\Schedule;

class Schedule
{
    public static function init(): void
    {
        add_action('init', [Calendar::class,      'register']);
        add_action('init', [CalendarAjax::class,  'register']);
    }
}
