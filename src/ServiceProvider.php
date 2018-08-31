<?php

namespace AdamJedlicka\Luna;

use Illuminate\Support\Facades\Log;
use AdamJedlicka\Luna\Support\Models;
use AdamJedlicka\Luna\Support\Resources;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/luna.php', 'luna');

        $this->app->bind('luna.models', Models::class);
        $this->app->bind('luna.resources', Resources::class);
    }

    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'luna');

        $this->publishes([
            __DIR__ . '/../public' => public_path('/vendor/luna'),
            __DIR__ . '/../config/luna.php' => config_path('luna.php'),
        ]);
    }
}
