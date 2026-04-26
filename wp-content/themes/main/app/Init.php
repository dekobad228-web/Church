<?php

namespace App;

use App\Vite\Assets;
use App\Mailer\Ajax;

class Init
{
    public static function init()
    {
        Assets::init();
        Ajax::init();
    }
}
