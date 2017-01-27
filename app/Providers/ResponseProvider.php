<?php

namespace App\Providers;

use App\Http\Responses\DownloadResponse;
use App\Http\Responses\StandardResponse;
use App\Http\Responses\UserStateResponse;
use Illuminate\Support\ServiceProvider;

class ResponseProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->instance('StandardResponse', new StandardResponse());
        $this->app->instance('DownloadResponse', new DownloadResponse());
    }
}
