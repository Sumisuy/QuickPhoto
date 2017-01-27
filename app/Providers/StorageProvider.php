<?php

namespace App\Providers;

use App\Services\Storage\ImageArchiver;
use App\Services\Storage\ZipArchiver;
use Illuminate\Support\ServiceProvider;

class StorageProvider extends ServiceProvider
{
    /**
     * BOOT
     * ---
     * @author MS
     */
    public function boot()
    {
        $user = null;
        try {
            $user = \JWTAuth::parseToken()->authenticate();
        } catch (\Exception $ex) {}

        ImageArchiver::setUser($user);
        ZipArchiver::setUser($user);
    }

    /**
     * REGISTER
     * ---
     * @author MS
     */
    public function register()
    {
        $this->app->singleton('ImageArchiver', new ImageArchiver());
        $this->app->singleton('ZipArchiver', new ZipArchiver());
    }
}
