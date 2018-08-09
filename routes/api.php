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

    Route::post('/resources/{name}', 'ResourceController@create')
        ->name('resources.create');

    Route::get('/resources/{name}/{id}', 'ResourceController@detail')
        ->name('resources.detail');

    Route::put('/resources/{name}/{id}', 'ResourceController@update')
        ->name('resources.update');

    Route::delete('/resources/{name}/{id}', 'ResourceController@delete')
        ->name('resources.delete');

});
