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
        if (auth()->guest()) {
            $path = 'guest/' . request('_token');
        } else {
            $path = 'user/' . (string)auth()->user()->id;
        }
        ImageStorage::setPathAdjustment($path);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('ImageStorage', new ImageStorage());
    }
}
