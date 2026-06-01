<?php

namespace App;

use App\Donate\DonateAjax;
use App\Vite\Assets;
use App\Mailer\Ajax as MailerAjax;
use App\Notes\Ajax as NotesAjax;
use App\Payment\Robokassa;
use App\Entity\Registry;
use App\Schedule\Schedule;

class Init
{
    public static function init()
    {
        Assets::init();

        MailerAjax::init();

        NotesAjax::init();
        DonateAjax::init();
        Robokassa::init();

        Registry::init();
        Schedule::init();
    }
}
