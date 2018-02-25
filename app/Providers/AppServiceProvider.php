<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use DB;
use Log;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        ///DB::listen(function($query) { Log::info(json_encode($query)); });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('httpclient', function()
        {
            return new \App\Libraries\Curl\HttpClientServices;
        });
		$this->app->bind('weixin', function()
        {
            return new \App\Libraries\Weixin\WeixinServices;
        });
		$this->app->bind('share', function()
        {
            return new \App\Libraries\Share\ShareServices;
        });
		$this->app->bind('helplib', function()
        {
            return new \App\Libraries\Helplib\HelpLibServices;
        });
    }
}
