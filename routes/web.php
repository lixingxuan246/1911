<?php

//注册 接口
Route::any('/reg','UserController@reg');



//登录 接口
Route::post('/login','LoginController@login');

//图片验证码
Route::get('/showImageCode','LoginController@showImageCode');

//图片验证码
Route::get('/getImgUrl','LoginController@getImageCodeUrl');


//发送短信验证码
Route::post('/sendMsgCode','MsgController@sendMsgCode');

//登录
Route::any('/login','Login\LoginController@login');


// 新闻列表

Route::any('/newsList','News\NewsController@newsList');
