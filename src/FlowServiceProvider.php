<?php

namespace CokeCancino\LaravelFlow;

use Illuminate\Support\ServiceProvider;

class FlowServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/config/config.php' => config_path('flow.php'),
        ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->bindFlowClass();
    }

    private function bindFlowClass()
    {
        $this->app->bind(Flow::class, function ($app) {
            $flow = new Flow();
            return $flow;
        });
    }

    public function provides()
    {
        return [Flow::class];
    }
}
