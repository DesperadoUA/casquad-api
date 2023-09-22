<?php
use Illuminate\Http\Request;

Route::namespace('Api')->group(function () {
    Route::get('currency/{id}', 'CurrencyController@show')->middleware('cash');
    Route::get('currencies/{id}', 'CurrencyController@category')->middleware('cash');

    Route::post('admin/currencies', 'AdminCurrencyController@index')->middleware('api_auth');
    Route::post('admin/currency/update', 'AdminCurrencyController@update')->middleware('api_auth');
    Route::post('admin/currency/delete', 'AdminCurrencyController@delete')->middleware('api_auth');
    Route::post('admin/currency/store', 'AdminCurrencyController@store')->middleware('api_auth');

    Route::post('admin/currency/category', 'AdminCurrencyCategoryController@index')->middleware('api_auth');
    Route::post('admin/currency/category/update', 'AdminCurrencyCategoryController@update')->middleware('api_auth');
    Route::post('admin/currency/category/delete', 'AdminCurrencyCategoryController@delete')->middleware('api_auth');
    Route::post('admin/currency/category/store', 'AdminCurrencyCategoryController@store')->middleware('api_auth');
    Route::post('admin/currency/category/{id}', 'AdminCurrencyCategoryController@show')->middleware('api_auth');

    Route::post('admin/currency/{id}', 'AdminCurrencyController@show')->middleware('api_auth');
});