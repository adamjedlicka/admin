<?php

Route::group([
    'namespace' => 'AdamJedlicka\Admin\Http\Controllers',
    'middleware' => ['web', 'auth'],
    'prefix' => config('admin.prefix'),
], function () {

    Route::get('/{any?}', function () {
        return view('admin::index');
    })->where('any', '.*');

});
