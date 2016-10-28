<?php

namespace Packages\Image;

use Illuminate\Support\ServiceProvider;

class ImageServiceProvider extends ServiceProvider
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
        // $this->app->bind();
        
        $this->app->singleton('imageprocessor', function () {
            return $this->app->make('Packages\Image\Processor');
        });
    }
}