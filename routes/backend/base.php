<?php
/**
 * Routes of this plugin
 */

Route::group(['middleware' => ['auth:admin']], function () {
    /**
     * GET
     */
    Route::get('/', 'AdsManagerController@index')->name('index');
    Route::get('/edit', 'AdsManagerController@index')->name('index');
    Route::get('/edit/{adID}', 'AdsManagerController@index')->name('index');
    Route::get('/generate-slug/{slug}/{adID}', 'AdsManagerController@generateSlug')->name('generateSlug');
    Route::get('/get-all', 'AdsManagerController@getAll')->name('getAll');
    Route::get('/delete/{adID}', 'AdsManagerController@delete')->name('delete');
    Route::get('/details/{adID}', 'AdsManagerController@details')->name('details');

    /**
     * POST
     */
    Route::post('/store', 'AdsManagerController@store')->name('store');

});