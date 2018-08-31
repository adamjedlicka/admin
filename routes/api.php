<?php

Route::group([
    'namespace' => 'AdamJedlicka\Luna\Http\Controllers',
    'middleware' => config('luna.middleware'),
    'prefix' => config('luna.prefix') . '/api',
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

    Route::get('/resources/{resource}/{resourceKey}', 'ShowController')
        ->name('resources.show');

    Route::get('/resources/{resource}/{resourceKey}/edit', 'EditController')
        ->name('resources.edit');

    Route::put('/resources/{resource}/{resourceKey}', 'UpdateController')
        ->name('resources.update');

    Route::delete('/resources/{resource}/{resourceKey}', 'DeleteController')
        ->name('resources.delete');

    /**
     * HasManyController
     */
    Route::get('/resources/{resource}/{resourceKey}/hasMany/{relationship}', 'HasManyController@index')
        ->name('resources.hasMany');

    /**
     * BelongsToManyController
     */
    Route::get('/resources/{resource}/{resourceKey}/belongsToMany/{relationship}', 'BelongsToManyController@index')
        ->name('resources.belongsToMany');

    Route::get('/resources/{resource}/{resourceKey}/belongsToMany/{relationship}/attach', 'BelongsToManyController@create')
        ->name('resources.belongsToMany.create');

    Route::post('/resources/{resource}/{resourceKey}/belongsToMany/{relationship}/attach', 'BelongsToManyController@store')
        ->name('resources.belongsToMany.store');

    Route::get('/resources/{resource}/{resourceKey}/belongsToMany/{relationship}/{relationshipKey}/edit', 'BelongsToManyController@edit')
        ->name('resources.belongsToMany.edit');

    Route::put('/resources/{resource}/{resourceKey}/belongsToMany/{relationship}/{relationshipKey}', 'BelongsToManyController@update')
        ->name('resources.belongsToMany.update');

    Route::delete('/resources/{resource}/{resourceKey}/belongsToMany/{relationship}/{relationshipKey}', 'BelongsToManyController@delete')
        ->name('resources.belongsToMany.delete');

});
