<?php

namespace xiaomi\multiUpload\Providers;

use Illuminate\Support\ServiceProvider;

class MultiUploadProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //publish the config file
        $this->publishes([__DIR__.'/../config/upload.php' => config_path('multi_upload.php')],'multi_upload');

        //load routes/upload.php
        $this->loadRoutesFrom(__DIR__.'/../routes/upload.php');

        //load views/upload.blade.php
        $this->loadViewsFrom(__DIR__.'/../views', 'upload');
        //publish views/upload
        $this->publishes([__DIR__.'/../views' => resource_path('views/vendor/upload')], 'multi_upload');

        //publish resources
        $this->publishes([
            __DIR__.'/../resources'=>public_path('multiUpload'),
        ],'multi_upload');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
