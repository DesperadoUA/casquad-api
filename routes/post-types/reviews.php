<?php
use Illuminate\Http\Request;

Route::namespace('Api')->group(function () {
    Route::get('review/{id}', 'ReviewController@show');
    Route::post('admin/reviews', 'AdminReviewController@index')->middleware('api_auth');
    Route::post('admin/review/update', 'AdminReviewController@update')->middleware('api_auth');
    Route::post('admin/review/delete', 'AdminReviewController@delete')->middleware('api_auth');

    Route::post('admin/review/{id}', 'AdminReviewController@show')->middleware('api_auth');
    //Route::post('admin/review/store', 'AdminReviewController@store')->middleware('api_auth');
});
