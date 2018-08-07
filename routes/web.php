<?php

Route::group([
    'namespace' => 'AdamJedlicka\Admin\Http\Controllers',
    'middleware' => 'web',
    'prefix' => 'admin',
], function () {

    Route::get('/{any?}', 'AdminController@index')
        ->where('any', '.*')
        ->name('admin.index');

});
