<?php

namespace AdamJedlicka\Admin\Providers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class BaseServiceProvider extends ServiceProvider
{
    public function register()
    {
        require_once __DIR__ . '/../Support/helpers.php';
    }

    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/../../routes/api.php');
        $this->loadRoutesFrom(__DIR__ . '/../../routes/web.php');
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'admin');

        $this->publishes([
            __DIR__ . '/../../public' => public_path('/vendor/admin'),
            __DIR__ . '/../../config/admin.php' => config_path('admin.php'),
        ]);
    }
}
