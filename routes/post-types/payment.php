<?php
use Illuminate\Http\Request;

Route::namespace('Api')->group(function () {
    Route::get('payment/{id}', 'PaymentController@show')->middleware('cash');
    Route::get('payments/{id}', 'PaymentController@category')->middleware('cash');

    Route::post('admin/payments', 'AdminPaymentController@index')->middleware('api_auth');
    Route::post('admin/payment/update', 'AdminPaymentController@update')->middleware('api_auth');
    Route::post('admin/payment/delete', 'AdminPaymentController@delete')->middleware('api_auth');
    Route::post('admin/payment/store', 'AdminPaymentController@store')->middleware('api_auth');

    Route::post('admin/payment/category', 'AdminPaymentCategoryController@index')->middleware('api_auth');
    Route::post('admin/payment/category/update', 'AdminPaymentCategoryController@update')->middleware('api_auth');
    Route::post('admin/payment/category/delete', 'AdminPaymentCategoryController@delete')->middleware('api_auth');
    Route::post('admin/payment/category/store', 'AdminPaymentCategoryController@store')->middleware('api_auth');
    Route::post('admin/payment/category/{id}', 'AdminPaymentCategoryController@show')->middleware('api_auth');

    Route::post('admin/payment/{id}', 'AdminPaymentController@show')->middleware('api_auth');
});