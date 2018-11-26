<?php

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
*/

Route::group(['middleware' => ['auth:admin', 'permission']], function () {
    Route::get('/', ['uses' => 'IndexController@index']);

    Route::get('home', ['uses' => 'IndexController@home', 'as' => 'admin.index.home']);
    Route::get('table', ['uses' => 'IndexController@table', 'as' => 'admin.index.table']);
    Route::get('form', ['uses' => 'IndexController@form', 'as' => 'admin.index.form']);
    Route::get('ajax', ['uses' => 'IndexController@ajax', 'as' => 'admin.index.ajax']);
    Route::post('formUpload', ['uses' => 'IndexController@formUpload', 'as' => 'admin.index.form_upload']);

    // 用户路由
    Route::resource('home_user','HomeUsersController')->names('admin.home_user');
    Route::resource('ubi', 'UserBehaviorLogsController')->names('admin.ubi');


    // 红包路由
    Route::resource('game_partition', 'GamePartitionsController',
        ['only' => ['index', 'create', 'store', 'edit', 'update', 'destroy']])->names('admin.game_partition');
    Route::resource('out_packet', 'OutPacketsController')->names('admin.out_packet');
    Route::resource('in_packet','InPacketsController')->names('admin.in_packet');

    Route::resource('transaction_info','TransactionInfosController')->names('admin.transaction_info');

    Route::resource('menu', 'MenusController', ['except' => 'show'])->names('admin.menu');
    Route::resource('permission', 'PermissionsController', ['except' => 'show'])->names('admin.permission');

    Route::get('role/{id}/permission', ['uses' => 'RolesController@permission', 'as' => 'admin.role.permission']);
    Route::resource('role', 'RolesController', ['except' => 'show'])->names('admin.role');

    Route::get('user/{id}/role', ['uses' => 'UsersController@role', 'as' => 'admin.user.role']);
    Route::get('user/{id}/permission', ['uses' => 'UsersController@permission', 'as' => 'admin.user.permission']);
    Route::resource('user', 'UsersController')->names('admin.user');

    Route::resource('keywords_type', 'KeywordsTypeController', ['except' => 'show'])->names('admin.keywords_type');
    Route::get('web_config/index','WebConfigController@index')->name('admin.web.index');
    Route::resource('keywords', 'KeywordsController', ['except' => 'show'])->names('admin.keywords');
    Route::resource('ad_positions', 'AdPositionsController')->names('admin.ad_positions');
    Route::resource('ad_managments', 'AdManagmentsController')->names('admin.ad_managments');
    
});
Route::get('login', ['uses' => 'AuthController@showLoginForm', 'as' => 'admin.login', 'middleware' => ['guest:admin']]);
Route::post('login', ['uses' => 'AuthController@login', 'as' => 'admin.doLogin']);

Route::post('profile', ['uses' => 'AuthController@profile', 'as' => 'admin.profile', 'middleware' => ['auth:admin']]);
Route::get('logout', ['uses' => 'AuthController@logout', 'as' => 'admin.logout', 'middleware' => ['auth:admin']]);
