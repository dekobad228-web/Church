<?php

namespace App;

class Helpers
{
    private static array $months = [
        0  => ['Январь',  'Января'],
        1  => ['Февраль', 'Февраля'],
        2  => ['Март',    'Марта'],
        3  => ['Апрель',  'Апреля'],
        4  => ['Май',     'Мая'],
        5  => ['Июнь',    'Июня'],
        6  => ['Июль',    'Июля'],
        7  => ['Август',  'Августа'],
        8  => ['Сентябрь', 'Сентября'],
        9  => ['Октябрь', 'Октября'],
        10 => ['Ноябрь',  'Ноября'],
        11 => ['Декабрь', 'Декабря'],
    ];

    public static function getWeekDayName(int $timestamp): string
    {
        $days = [
            'Воскресенье',
            'Понедельник',
            'Вторник',
            'Среда',
            'Четверг',
            'Пятница',
            'Суббота',
        ];

        return $days[date('w', $timestamp)] ?? '';
    }

    public static function getMonthName(int $monthNum): string
    {
        return self::$months[$monthNum][0] ?? '';
    }

    public static function getMonthNameGenitive(int $monthNum): string
    {
        return self::$months[$monthNum][1] ?? '';
    }

    public static function getMonthNumByName(string $name): int
    {
        foreach (self::$months as $num => $names) {
            if (in_array($name, $names, true))
                return $num;
        }
        return 0;
    }
}
