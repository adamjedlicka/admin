<?php

Route::group([
    'namespace' => 'AdamJedlicka\Admin\Http\Controllers',
    'middleware' => 'api',
    'prefix' => config('admin.prefix') . '/api',
], function () {

    /**
     * ResourceController
     */

    Route::get('/resources', 'ResourceController@list')
        ->name('resources.list');

    Route::get('/resources/{name}', 'ResourceController@index')
        ->name('resources.index');

    Route::get('/resources/{name}/create', 'ResourceController@create')
        ->name('resources.create');

    Route::post('/resources/{name}', 'ResourceController@store')
        ->name('resources.store');

    Route::get('/resources/{name}/{key}', 'ResourceController@detail')
        ->name('resources.detail');

    Route::get('/resources/{name}/{key}/edit', 'ResourceController@edit')
        ->name('resources.edit');

    Route::put('/resources/{name}/{key}', 'ResourceController@update')
        ->name('resources.update');

    Route::delete('/resources/{name}/{key}', 'ResourceController@delete')
        ->name('resources.delete');

    /**
     * HasManyController
     */
    Route::get('/resources/{name}/{key}/hasMany/{relationship}', 'HasManyController@index')
        ->name('resources.hasMany');

});
