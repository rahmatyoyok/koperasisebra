<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['namespace' => 'Api'], function()
{
    Route::group(['prefix' => 'usaha'], function()
    {
        Route::get('getInfoSupplier', 'UsahaController@getInfoSupplier');
        Route::get('getInfoWO', 'UsahaController@getInfoWO');
        
    });
    
    Route::group(['prefix' => 'select2'], function()
    {   
        Route::get('listMasterPO', 'Select2Controller@listMasterPO');
        Route::get('person', 'Select2Controller@PersonData');
        Route::get('personInvestasi', 'Select2Controller@PersonDataSaldoNotZero');
        Route::post('testToken', 'Select2Controller@testToken');
        Route::get('jenisbungapinjaman', 'Select2Controller@listJenisBunga');

        Route::get('coaaktif', 'Select2Controller@listCoaAktif');
        Route::get('coaaset', 'Select2Controller@listCoaAsset');
    });

    Route::group(['prefix' => 'table'], function()
    {
        Route::get('saldoinvesatasi', 'TableController@getSaldoPerKaryawan');
        Route::get('checkstatusposting', 'TableController@checkButtonKalkulasi');
    });

    Route::group(['prefix' => 'public'], function()
    {
        Route::get('login', 'PublicController@login');

    });
});
