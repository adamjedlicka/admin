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
    Route::get('/resources/{resource}', 'IndexController')
        ->name('resources.index');

    Route::get('/resources/{resource}/create', 'CreateController')
        ->name('resources.create');

    Route::post('/resources/{resource}', 'StoreController')
        ->name('resources.store');

    Route::get('/resources/{resource}/{key}', 'DetailController')
        ->name('resources.detail');

    Route::get('/resources/{resource}/{key}/edit', 'EditController')
        ->name('resources.edit');

    Route::put('/resources/{resource}/{key}', 'UpdateController')
        ->name('resources.update');

    Route::delete('/resources/{resource}/{key}', 'DeleteController')
        ->name('resources.delete');

    /**
     * BelongsToController
     */
    Route::get('/relationships/{resource}/belongsTo/{relationship}', 'BelongsToController@index')
        ->name('relationships.belongsTo');

    /**
     * HasManyController
     */
    Route::get('/relationships/{resource}/{key}/hasMany/{relationship}', 'HasManyController@index')
        ->name('relationships.hasMany');

    /**
     * BelongsToManyController
     */
    Route::get('/relationships/{resource}/{key}/belongsToMany/{relationship}', 'BelongsToManyController@index')
        ->name('resources.belongsToMany');

    Route::get('/relationships/{resource}/{key}/belongsToMany/{relationship}/attach', 'BelongsToManyController@create')
        ->name('resources.belongsToMany.create');

    Route::post('/relationships/{resource}/{key}/belongsToMany/{relationship}/attach', 'BelongsToManyController@attach')
        ->name('resources.belongsToMany.attach');

});
