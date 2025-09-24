<?php
use Illuminate\Http\Request;

Route::namespace('Api')->group(function () {
    Route::get('author/articles/{id}', 'AuthorController@relativeArticles')->middleware('cash');
    Route::get('author/casinos/{id}', 'AuthorController@relativeCasinos')->middleware('cash');
    Route::get('author/{id}', 'AuthorController@show')->middleware('cash');
    Route::get('authors/{id}', 'AuthorController@category')->middleware('cash');

    Route::post('admin/authors', 'AdminAuthorController@index')->middleware('api_auth');
    Route::post('admin/author/update', 'AdminAuthorController@update')->middleware('api_auth');
    Route::post('admin/author/delete', 'AdminAuthorController@delete')->middleware('api_auth');
    Route::post('admin/author/store', 'AdminAuthorController@store')->middleware('api_auth');

    Route::post('admin/author/category', 'AdminAuthorCategoryController@index')->middleware('api_auth');
    Route::post('admin/author/category/update', 'AdminAuthorCategoryController@update')->middleware('api_auth');
    Route::post('admin/author/category/delete', 'AdminAuthorCategoryController@delete')->middleware('api_auth');
    Route::post('admin/author/category/store', 'AdminAuthorCategoryController@store')->middleware('api_auth');
    Route::post('admin/author/category/{id}', 'AdminAuthorCategoryController@show')->middleware('api_auth');

    Route::post('admin/author/{id}', 'AdminAuthorController@show')->middleware('api_auth');
});
