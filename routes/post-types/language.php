<?php
use Illuminate\Http\Request;

Route::namespace('Api')->group(function () {
    Route::get('language/{id}', 'LanguageController@show')->middleware('cash');
    Route::get('languages/{id}', 'LanguageController@category')->middleware('cash');

    Route::post('admin/languages', 'AdminLanguageController@index')->middleware('api_auth');
    Route::post('admin/language/update', 'AdminLanguageController@update')->middleware('api_auth');
    Route::post('admin/language/delete', 'AdminLanguageController@delete')->middleware('api_auth');
    Route::post('admin/language/store', 'AdminLanguageController@store')->middleware('api_auth');

    Route::post('admin/language/category', 'AdminLanguageCategoryController@index')->middleware('api_auth');
    Route::post('admin/language/category/update', 'AdminLanguageCategoryController@update')->middleware('api_auth');
    Route::post('admin/language/category/delete', 'AdminLanguageCategoryController@delete')->middleware('api_auth');
    Route::post('admin/language/category/store', 'AdminLanguageCategoryController@store')->middleware('api_auth');
    Route::post('admin/language/category/{id}', 'AdminLanguageCategoryController@show')->middleware('api_auth');

    Route::post('admin/language/{id}', 'AdminLanguageController@show')->middleware('api_auth');
});