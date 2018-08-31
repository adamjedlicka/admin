<?php

Route::group([
    'namespace' => 'AdamJedlicka\Luna\Http\Controllers',
    'middleware' => config('luna.middleware'),
    'prefix' => config('luna.prefix'),
], function () {

    Route::get('/{any?}', function () {
        return view('luna::index');
    })->where('any', '.*');

});
