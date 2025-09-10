<?php
use Illuminate\Http\Request;

Route::namespace('Api')->group(function () {
    Route::get('review/{id}', 'ReviewController@show');
    Route::post('review/store', 'ReviewController@store');
    Route::post('admin/reviews', 'AdminReviewController@index')->middleware('api_auth');
    Route::post('admin/review/update', 'AdminReviewController@update')->middleware('api_auth');
    Route::post('admin/review/delete', 'AdminReviewController@delete')->middleware('api_auth');

    Route::post('admin/review/{id}', 'AdminReviewController@show')->middleware('api_auth');
});
