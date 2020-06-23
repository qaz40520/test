<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


Route::group(['prefix' => 'user'], function(){
    Route::group(['prefix' => 'auth'], function(){
        //Facebook登入
        Route::get('/fb-login', 'FBAuthController@facebookLogin');
        //Facebook登入重新導向授權資料處理
        Route::get('/fb-callback', 'FBAuthController@facebookCallback');

        Route::get('/line-login', 'LINEAuthController@lineLogin');
        Route::get('/line-callback', 'LINEAuthController@lineCallback');
    });
});
