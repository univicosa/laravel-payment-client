<?php

Route::group(['middleware' => ['api', \Barryvdh\Cors\HandleCors::class], 'as' => 'payment.', 'prefix' => 'api',
    'namespace' => 'Payments\Client\Http\Controllers'], function() {

    Route::post('payment', 'ReturnController@receive')->name('receive');
});
