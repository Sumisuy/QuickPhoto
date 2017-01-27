<?php

namespace App\Providers;

use App\Services\Tools\ToolResize;
use Illuminate\Support\ServiceProvider;

class ToolsProvider extends ServiceProvider
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
        $this->app->instance('ToolResize', new ToolResize());
    }
}
