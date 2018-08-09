<?php

Route::group([
    'namespace' => 'AdamJedlicka\Admin\Http\Controllers',
    'middleware' => 'api',
    'prefix' => config('admin.prefix') . '/api',
], function () {

    Route::get('/resources', 'ResourceController@list')
        ->name('resources.list');

    Route::get('/resources/{name}', 'ResourceController@index')
        ->name('resources.index');

    Route::get('/resources/{name}/{id}', 'ResourceController@detail')
        ->name('resources.detail');

    Route::post('/resources/{name}/{id}', 'ResourceController@update')
        ->name('resources.update');

});
