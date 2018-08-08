<?php

Route::group([
    'namespace' => 'AdamJedlicka\Admin\Http\Controllers',
    'middleware' => 'api',
    'prefix' => config('admin.prefix') . '/api',
], function () {

    Route::get('/resources', 'ResourceController@index')
        ->name('resources.index');

});
