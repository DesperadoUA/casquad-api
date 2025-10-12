<?php
use Illuminate\Http\Request;

Route::namespace('Api')->group(function () {
    Route::get('article/{id}', 'ArticleController@show')->middleware('cash');
    Route::get('articles/{id}', 'ArticleController@category')->middleware('cash');
    Route::get('article/reviews/{id}', 'ArticleController@reviews')->middleware(['cash', 'geo']);

    Route::post('admin/articles', 'AdminArticleController@index')->middleware('api_auth');
    Route::post('admin/article/update', 'AdminArticleController@update')->middleware('api_auth');
    Route::post('admin/article/delete', 'AdminArticleController@delete')->middleware('api_auth');
    Route::post('admin/article/store', 'AdminArticleController@store')->middleware('api_auth');

    Route::post('admin/article/category', 'AdminArticleCategoryController@index')->middleware('api_auth');
    Route::post('admin/article/category/update', 'AdminArticleCategoryController@update')->middleware('api_auth');
    Route::post('admin/article/category/delete', 'AdminArticleCategoryController@delete')->middleware('api_auth');
    Route::post('admin/article/category/store', 'AdminArticleCategoryController@store')->middleware('api_auth');
    Route::post('admin/article/category/{id}', 'AdminArticleCategoryController@show')->middleware('api_auth');

    Route::post('admin/article/{id}', 'AdminArticleController@show')->middleware('api_auth');
});
