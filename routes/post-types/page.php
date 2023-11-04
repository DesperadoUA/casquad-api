<?php
use Illuminate\Http\Request;

Route::namespace('Api')->group(function () {
    Route::post('admin/pages', 'AdminPageController@index')->middleware('api_auth');
    Route::post('admin/pages/update', 'AdminPageController@update')->middleware('api_auth');
    Route::post('admin/pages/{id}', 'AdminPageController@show')->middleware('api_auth');
    /* Front */
    Route::get('pages/'.config('constants.PAGES.MAIN'), 'PageController@main')->middleware('cash');
    Route::get('pages/'.config('constants.PAGES.BONUSES'), 'PageController@bonuses')->middleware('cash');
    Route::get('pages/'.config('constants.PAGES.GAMES'), 'PageController@games')->middleware('cash');
    Route::get('pages/'.config('constants.PAGES.NEWS'), 'PageController@news')->middleware('cash');
    Route::get('pages/'.config('constants.PAGES.SITE_MAP'), 'PageController@siteMap')->middleware('cash');
    Route::get('pages/'.config('constants.PAGES.SEARCH'), 'PageController@search');
    Route::get(config('constants.PAGES.SEARCH'), 'PageController@search');
});