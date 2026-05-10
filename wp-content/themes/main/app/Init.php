<?php

namespace App;

use App\Vite\Assets;
use App\Mailer\Ajax;
use App\Entity\Registry;
use App\Schedule\Schedule;

class Init
{
    public static function init()
    {
        Assets::init();
        Ajax::init();
        Registry::init();
        Schedule::init();
    }
}
