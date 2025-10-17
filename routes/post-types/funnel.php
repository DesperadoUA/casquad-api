<?php
use Illuminate\Http\Request;

Route::namespace('Api')->group(function () {
    Route::get('funnel/{id}', 'FunnelController@show')->middleware('cash');
    Route::get('funnels/{id}', 'FunnelController@category')->middleware('cash');

    Route::post('admin/funnels', 'AdminFunnelController@index')->middleware('api_auth');
    Route::post('admin/funnel/update', 'AdminFunnelController@update')->middleware('api_auth');
    Route::post('admin/funnel/delete', 'AdminFunnelController@delete')->middleware('api_auth');
    Route::post('admin/funnel/store', 'AdminFunnelController@store')->middleware('api_auth');

    Route::post('admin/funnel/category', 'AdminFunnelCategoryController@index')->middleware('api_auth');
    Route::post('admin/funnel/category/update', 'AdminFunnelCategoryController@update')->middleware('api_auth');
    Route::post('admin/funnel/category/delete', 'AdminFunnelCategoryController@delete')->middleware('api_auth');
    Route::post('admin/funnel/category/store', 'AdminFunnelCategoryController@store')->middleware('api_auth');
    Route::post('admin/funnel/category/{id}', 'AdminFunnelCategoryController@show')->middleware('api_auth');

    Route::post('admin/funnel/{id}', 'AdminFunnelController@show')->middleware('api_auth');
});
