<?php

Route::group(['prefix' => 'artisan'], function () {
    Route::get('/{option?}', '\Bestmomo\NiceArtisan\Http\Controllers\NiceArtisanController@show');
    Route::post('item/{class}', '\Bestmomo\NiceArtisan\Http\Controllers\NiceArtisanController@command');
});