<?php

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
    return response('Hello, world!');
});

Route::post('/auth/oauth', 'Auth\OAuthController@process');
Route::get('/auth/login', 'Auth\DisposableLoginController@authenticate');
Route::get('/auth/logout', 'Auth\DisposableLoginController@logout');
Route::get('/auth/jump', 'Auth\OAuthController@jump');
Route::get('/auth/fake/{id}', 'Auth\OAuthController@fake');


//test备用，之后会删除
Route::post('/test', 'ChattingController@test');
Route::get('/test/chatting', 'ChattingController@index');
Route::get('/test/chattingRoom', 'ChattingController@chattingRoom');