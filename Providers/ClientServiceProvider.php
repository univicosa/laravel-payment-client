<?php

namespace Payments\Client\Providers;

use Illuminate\Support\ServiceProvider;
use Payments\Client\Service\Client;

class ClientServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerConfig();
        $this->loadRoutesFrom(__DIR__ . '/../Http/routes.php');
        $this->app->singleton('payment', function ($app) {
            return new Client();
        });
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            __DIR__.'/../Config/config.php' => config_path('payment.php'),
        ], 'payment');
        $this->mergeConfigFrom(
            __DIR__.'/../Config/config.php', 'payment'
        );
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }
}
