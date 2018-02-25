<?php

namespace App\Libraries\Helplib\Facades;

use Illuminate\Support\Facades\Facade;

class HelpLibServices extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'helplib';
    }
}