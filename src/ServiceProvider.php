<?php

namespace AdamJedlicka\Admin;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    public function register()
    {
        include 'helpers.php';
    }

    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');
        // $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'admin');

        $this->publishes([
            __DIR__ . '/../public' => public_path('/vendor/admin')
        ], 'public');
    }
}
