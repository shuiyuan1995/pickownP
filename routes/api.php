<?php


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
    Route::get('get_reward_count', ['uses' => 'Api\InfoController@getRewardCount', 'as' => 'api.web.get_reward_count']);
});

Route::group(['namespace' => 'Api'], function () {
    Route::get('login', ['uses' => 'InfoController@login', 'as' => 'api.login']);


    // Api
    Route::post('issus_packet',
        ['uses' => 'ApiController@issus_packet', 'as' => 'api.issus_packet'])->middleware('checktoken');
    Route::post('income_packet',
        ['uses' => 'ApiController@income_packet', 'as' => 'api.income_packet'])->middleware('checktoken');
    Route::get('my_issus_packet',
        ['uses' => 'ApiController@my_issus_packet', 'as' => 'api.my_issus_packet'])->middleware('checktoken');
    Route::get('my_income_packet',
        ['uses' => 'ApiController@my_income_packet', 'as' => 'api.my_income_packet'])->middleware('checktoken');
    Route::get('my_packet',
        ['uses' => 'ApiController@my_packets', 'as' => 'api.my_packet'])->middleware('checktoken');
    Route::get('red_packet',
        ['uses' => 'ApiController@red_packet', 'as' => 'api.red_packet'])->middleware('checktoken');
    Route::get('get_tixian_info',
        ['uses' => 'ApiController@getRewardMoney', 'as' => 'api.get_reward_money'])->middleware('checktoken');
    Route::post('post_tixian',
        ['uses' => 'ApiController@postRewardMoney', 'as' => 'api.post_tixian'])->middleware('checktoken');
    Route::post('close_packet',
        ['uses' => 'ApiController@close_packet', 'as' => 'api.close_packet'])->middleware('checktoken');
    Route::post('post_income_packet',
        ['uses' => 'ApiController@post_income_packet', 'as' => 'api.post_income_packet'])->middleware('checktoken');
    // Info
    Route::get('getDayUserRankList', ['uses' => 'ApiController@getDayUserRankList', 'as' => 'api.getDayUserRankList']);
    Route::get('get_info', ['uses' => 'InfoController@getInfo', 'as' => 'api.get_info']);
    Route::get('money_list', ['uses' => 'InfoController@moneyList', 'as' => 'api.money_list']);
    Route::get('get_money_list', ['uses' => 'InfoController@getMoneyList', 'as' => 'api.get_money_list']);
    Route::get('get_table_rows', ['uses' => 'InfoController@getTableRows', 'as' => 'api.get_table_rows']);
    Route::get('get_pending', ['uses' => 'InfoController@getPending', 'as' => 'api.pending']);
    Route::get('clear_log', ['uses' => 'InfoController@clearLog', 'as' => 'api.clear_log']);
    Route::get('get_paihangbang',
        ['uses'=>'InfoController@getPaihangbang','api.get_paihangbang']);
});