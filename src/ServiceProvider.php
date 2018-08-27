<?php

namespace AdamJedlicka\Admin;

use Illuminate\Support\Facades\Log;
use AdamJedlicka\Admin\Support\Models;
use AdamJedlicka\Admin\ResourceService;
use AdamJedlicka\Admin\Support\Resources;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    public function register()
    {
        $this->app->bind('admin.models', Models::class);
        $this->app->bind('admin.resources', Resources::class);
    }

    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'admin');

        $this->publishes([
            __DIR__ . '/../public' => public_path('/vendor/admin'),
            __DIR__ . '/../config/admin.php' => config_path('admin.php'),
        ]);
    }
}
