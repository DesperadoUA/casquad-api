<?php
use Illuminate\Http\Request;

Route::namespace('Api')->group(function () {
    Route::post('admin/pages', 'AdminPageController@index')->middleware('api_auth');
    Route::post('admin/pages/update', 'AdminPageController@update')->middleware('api_auth');
    Route::post('admin/pages/{id}', 'AdminPageController@show')->middleware('api_auth');
    /* Front */
    Route::get('pages/'.config('constants.PAGES.MAIN'), 'PageController@main')->middleware(['cash', 'geo']);
    Route::get('pages/'.config('constants.PAGES.BONUSES'), 'PageController@bonuses')->middleware(['cash', 'geo']);
    Route::get('pages/'.config('constants.PAGES.GAMES'), 'PageController@games')->middleware('cash');
    Route::get('pages/'.config('constants.PAGES.NEWS'), 'PageController@news')->middleware('cash');
    Route::get('pages/'.config('constants.PAGES.SITE_MAP'), 'PageController@siteMap')->middleware('cash');
    Route::get('pages/'.config('constants.PAGES.SEARCH'), 'PageController@search');
    Route::get('pages/'.config('constants.PAGES.DEPOSIT_ONE_DOLLAR'), 'PageController@default')->middleware('cash');
    Route::get('pages/'.config('constants.PAGES.DEPOSIT_FIVE_DOLLAR'), 'PageController@default')->middleware('cash');
    Route::get('pages/'.config('constants.PAGES.DEPOSIT_TEN_DOLLAR'), 'PageController@default')->middleware('cash');
    Route::get('pages/'.config('constants.PAGES.DEPOSIT_TWENTY_DOLLAR'), 'PageController@default')->middleware('cash');
    Route::get('pages/'.config('constants.PAGES.NO_DEPOSIT_BONUS'), 'PageController@default')->middleware('cash');
    Route::get('pages/'.config('constants.PAGES.FREE_SPINS'), 'PageController@default')->middleware('cash');
    Route::get('pages/'.config('constants.PAGES.PAYMENTS'), 'PageController@default')->middleware('cash');
    Route::get('pages/'.config('constants.PAGES.CASINO_APPS'), 'PageController@default')->middleware('cash');
    Route::get('pages/'.config('constants.PAGES.CRYPTO_CASINOS'), 'PageController@default')->middleware('cash');
    Route::get('pages/'.config('constants.PAGES.NO_WAGERING'), 'PageController@default')->middleware('cash');
    Route::get('pages/'.config('constants.PAGES.CASINO_GAMES'), 'PageController@default')->middleware('cash');
    Route::get('pages/'.config('constants.PAGES.VIP_CASINOS'), 'PageController@default')->middleware('cash');
    Route::get('pages/'.config('constants.PAGES.FREE_PLAY'), 'PageController@default')->middleware('cash');
    Route::get('pages/'.config('constants.PAGES.BONUS_ROOM_CASINO'), 'PageController@bonusRoomCasino')->middleware('cash');
    Route::get(config('constants.PAGES.SEARCH'), 'PageController@search');
    Route::get('pages/{id}', 'PageController@default')->middleware('cash'); 
});