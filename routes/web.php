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

//Route::any('/login','UserController@reg');
//Route::get('/test','LoginController@test');
//Route::get('/showImageCode','LoginController@showImageCode');


//Route::get('/getImgUrl','LoginController@getImageCodeUrl');
//Route::get('/test23','LoginController@test23');

//Route::post('/sendMsgCode','MsgController@sendMsgCode');

//Route::post('/ok','MsgController@ok');
//Route::get('/oks','MsgController@oks');

Route::any('/logins','Pc\PcController@login');
Route::any('/clickNumber','Pc\PcController@clickNumber');//浏览量
Route::any('/clickCount','Pc\PcController@clickCount');//点赞


//注册 接口
Route::any('/reg','UserController@reg');




//登录 接口
//Route::post('/login','LoginController@login');

//图片验证码
Route::get('/showImageCode','LoginController@showImageCode');

//图片验证码
Route::get('/getImgUrl','LoginController@getImageCodeUrl');


//发送短信验证码
Route::post('/sendMsgCode','MsgController@sendMsgCode');



// 新闻列表


Route::any('/newsList','News\NewsController@newsList');

Route::any('/newsList','News\NewsController@newsList');




Route::any('/newsList','api\NewsController@newsList'); //news列表


