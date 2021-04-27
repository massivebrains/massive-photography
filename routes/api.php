<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::group(['prefix' => 'auth', 'namespace' => 'Auth', 'middleware' => 'guest'], function () {
    Route::post('/login', 'LoginController@index');
    Route::post('/register', 'RegisterController@index');
    Route::get('/activate/{otp}', 'RegisterController@activate');
});

Route::group(['namespace' => 'Admin', 'prefix' => 'admin', 'middleware' => ['auth:api', 'admin']], function () {
    Route::group(['namespace' => 'Orders'], function () {
        Route::get('/orders', 'OrdersController@index');
        Route::put('/order/asign-photographer', 'OrdersController@assignPhotographer');
    });
    
    Route::get('/accounts', 'Accounts\AccountsController@index');
});

Route::group(['namespace' => 'User', 'prefix' => 'user', 'middleware' => ['auth:api', 'user']], function () {
    Route::group(['namespace' => 'Orders'], function () {
        Route::post('/order/create', 'PlaceOrderController@index');
        Route::get('/orders', 'OrdersController@index');
        Route::put('/order/{id}/{action}', 'OrdersController@status');
    });
});

Route::group(['namespace' => 'Photographer', 'prefix' => 'photographer', 'middleware' => ['auth:api', 'photographer']], function () {
    Route::group(['namespace' => 'Orders'], function () {
        Route::get('/orders', 'OrdersController@index');
        Route::post('/order/upload', 'UploadController@index');
    });
});
