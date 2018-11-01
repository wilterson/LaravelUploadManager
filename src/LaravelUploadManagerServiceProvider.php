<?php

namespace WiltersonGarcia\LaravelUploadManager;

use Illuminate\Support\ServiceProvider;

class LaravelUploadManagerServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'wiltersongarcia');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'wiltersongarcia');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // $this->loadRoutesFrom(__DIR__.'/routes.php');

        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/laraveluploadmanager.php', 'laraveluploadmanager');

        // Register the service the package provides.
        $this->app->singleton('laraveluploadmanager', function ($app) {
            return new LaravelUploadManager;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['laraveluploadmanager'];
    }
    
    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole()
    {
        // Publishing the configuration file.
        $this->publishes([
            __DIR__.'/../config/laraveluploadmanager.php' => config_path('laraveluploadmanager.php'),
        ], 'laraveluploadmanager.config');

        // Publishing the views.
        /*$this->publishes([
            __DIR__.'/../resources/views' => base_path('resources/views/vendor/wiltersongarcia'),
        ], 'laraveluploadmanager.views');*/

        // Publishing assets.
        /*$this->publishes([
            __DIR__.'/../resources/assets' => public_path('vendor/wiltersongarcia'),
        ], 'laraveluploadmanager.views');*/

        // Publishing the translation files.
        /*$this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/wiltersongarcia'),
        ], 'laraveluploadmanager.views');*/

        // Registering package commands.
        // $this->commands([]);
    }
}
