<?php

Route::group([
    'namespace' => 'AdamJedlicka\Admin\Http\Controllers',
    'middleware' => 'web',
], function () {

    Route::get('/admin/{any?}', 'AdminController@index')
        ->where('any', '.*')
        ->name('admin.index');

});
