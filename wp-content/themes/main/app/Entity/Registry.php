<?php

namespace App\Entity;

class Registry
{
    private static array $entities = [
        Notes::class,
    ];

    public static function register(): void
    {
        foreach (self::$entities as $entity) {
            $entity::register();
        }
    }

    public static function init(): void
    {
        add_action('init', [self::class, 'register']);
    }
}
