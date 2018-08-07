<?php

Route::group([
    'namespace' => 'AdamJedlicka\Admin\Http\Controllers',
    'middleware' => 'api',
    'prefix' => 'admin/api',
], function () {

    Route::get('/resources', 'ResourceController@index')
        ->name('resources.index');

});
