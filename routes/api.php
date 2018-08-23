<?php

Route::group([
    'namespace' => 'AdamJedlicka\Admin\Http\Controllers',
    'middleware' => [
        \Illuminate\Cookie\Middleware\EncryptCookies::class,
        \Illuminate\Auth\Middleware\Authenticate::class,
    ],
    'prefix' => config('admin.prefix') . '/api',
], function () {

    /**
     * AdminController
     */
    Route::get('/resources', 'AdminController@resources')
        ->name('admin.resources');

    /**
     * ResourceController
     */
    Route::get('/resources/{name}', 'IndexController')
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

    /**
     * BelongsToManyController
     */
    Route::get('/resources/{name}/{key}/belongsToMany/{relationship}', 'BelongsToManyController@index')
        ->name('resources.belongsToMany');

    Route::get('/resources/{name}/{key}/belongsToMany/{relationship}/attach', 'BelongsToManyController@create')
        ->name('resources.belongsToMany.create');

    Route::post('/resources/{name}/{key}/belongsToMany/{relationship}/attach/{relatedKey}', 'BelongsToManyController@attach')
        ->name('resources.belongsToMany.attach');

    Route::delete('/resources/{name}/{key}/belongsToMany/{relationship}/detach/{relatedKey}', 'BelongsToManyController@detach')
        ->name('resources.belongsToMany.detach');

});
