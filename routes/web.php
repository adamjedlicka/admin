<?php

Route::group([
    'namespace' => 'AdamJedlicka\Admin\Http\Controllers',
    'middleware' => config('admin.middleware'),
    'prefix' => config('admin.prefix'),
], function () {

    Route::get('/{any?}', function () {
        return view('admin::index');
    })->where('any', '.*');

});
