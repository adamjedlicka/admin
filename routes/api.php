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

    Route::get('/resources/{name}/create', 'ResourceController@create')
        ->name('resources.create');

    Route::post('/resources/{name}', 'ResourceController@store')
        ->name('resources.store');

    Route::get('/resources/{name}/{id}', 'ResourceController@detail')
        ->name('resources.detail');

    Route::get('/resources/{name}/{id}/edit', 'ResourceController@edit')
        ->name('resources.edit');

    Route::put('/resources/{name}/{id}', 'ResourceController@update')
        ->name('resources.update');

    Route::delete('/resources/{name}/{id}', 'ResourceController@delete')
        ->name('resources.delete');

});
