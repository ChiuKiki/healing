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

Route::get('user', 'UserController@get_info');
Route::get('search', 'SearchController@process');

/*
 *  Classical heal module
 */
Route::post('heal', 'HealController@create');
Route::post('story', 'StoryController@create');
/*
 *  Story heal module
 */
Route::post('story/{id}', 'StoryController@get_info');

//用户
//个人信息更新
Route::post('/user/update','UserController@updateInfo');


//举报机制
//意见反馈
Route::post('/feedback/suggestion','FeedbackController@suggestion');
//海螺举报
Route::post('/feedback/report','FeedbackController@report');
