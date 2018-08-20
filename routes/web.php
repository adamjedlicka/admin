<?php

Route::group([
    'namespace' => 'AdamJedlicka\Admin\Http\Controllers',
    'middleware' => 'web',
    'prefix' => config('admin.prefix'),
], function () {

    Route::get('/{any?}', 'SpaController')
        ->where('any', '.*');

});
