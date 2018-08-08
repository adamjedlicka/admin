<?php

Route::group([
    'namespace' => 'AdamJedlicka\Admin\Http\Controllers',
    'middleware' => 'web',
    'prefix' => config('admin.prefix'),
], function () {

    Route::get('/{any?}', 'AdminController@index')
        ->where('any', '.*')
        ->name('admin.index');

});
