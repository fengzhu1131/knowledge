<?php

namespace App\Libraries\Curl\Facades;

use Illuminate\Support\Facades\Facade;

class HttpClientServices extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'httpclient';
    }
}