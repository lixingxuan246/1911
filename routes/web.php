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
    return view('welcome');
});

Route::any('/login','UserController@reg');
Route::get('/test','LoginController@test');
Route::get('/showImageCode','LoginController@showImageCode');


Route::get('/getImgUrl','LoginController@getImageCodeUrl');
Route::get('/test23','LoginController@test23');

Route::post('/sendMsgCode','MsgController@sendMsgCode');

Route::post('/ok','MsgController@ok');
Route::get('/oks','MsgController@oks');
