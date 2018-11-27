<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'web'], function () {
    Route::get('city', ['uses' => 'Api\WebController@city', 'as' => 'api.web.city']);
    Route::post('upload', ['uses' => 'Api\WebController@upload', 'as' => 'api.web.upload']);
    Route::get('permission', ['uses' => 'Api\WebController@permission', 'as' => 'api.web.permission']);
    Route::get('menu', ['uses' => 'Api\WebController@menu', 'as' => 'api.web.menu']);
    Route::get('role', ['uses' => 'Api\WebController@role', 'as' => 'api.web.role']);
    Route::get('keywords_type', ['uses' => 'Api\WebController@keywordsType', 'as' => 'api.web.keywords_type']);
    Route::get('keywords', ['uses' => 'Api\WebController@keywords', 'as' => 'api.web.keywords']);

    Route::any('unique', ['uses' => 'Api\WebController@unique', 'as' => 'api.web.unique']);
});

Route::group(['namespace' => 'Api'], function () {
    Route::get('login', ['uses' => 'ApiControllerr@login', 'as' => 'api.login']);
    Route::get('game_partition',['uses'=>'ApiControllerr@game_partition','as'=>'api.game_partition']);
    Route::get('all_game_gifts',['uses'=>'ApiControllerr@all_game_gifts','as'=>'api.all_game_gifts']);
    Route::get('just_mine_game_gifts',['uses'=>'ApiControllerr@just_mine_game_gifts','as'=>'api.just_mine_game_gifts']);
    Route::get('web_info',['uses'=>'ApiControllerr@web_info','as'=>'api.web_info']);
    Route::get('allowns_list',['uses'=>'ApiControllerr@allowns_list','as'=>'api.allowns_list']);
});