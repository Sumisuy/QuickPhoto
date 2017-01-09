<?php

namespace App\Providers;

use App\Services\Storage\ImageStorage;
use Illuminate\Support\ServiceProvider;

class AppImageStorageProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->instance('ImageStorage', new ImageStorage());
    }
}
