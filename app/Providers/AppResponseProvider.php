<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Http\Responses\StandardResponse;
use App\Http\Responses\DownloadResponse;

class AppResponseProvider extends ServiceProvider
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
        $this->app->instance('DownloadResponse', new DownloadResponse());
        $this->app->instance('StandardResponse', new StandardResponse());
    }
}
