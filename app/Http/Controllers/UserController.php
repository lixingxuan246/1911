<?php

namespace App\Http\Controllers;

use App\Exceptions\ApiException;
use Illuminate\Http\Request;
use App\models\MsgModel;
use App\models\UserModel;
class UserController extends CommonController
{
    /*
     * 注册接口
     * */
    public function reg(Request $request){
        $tt = $request -> post('tt');
        $phone = $request -> post('phone');

        $msg_code = $request -> post('user_code');
        $pwd = $request -> post('pwd');


        #验证短信验证码 1，验证短信验证码是否过期 2，验证码是否正确
        $msg_model = new MsgModel();
        $where = [
            ['phone','=',$phone],
            ['type' ,'=',1]
        ];
        $msg_obj = $msg_model -> where( $where )
            ->orderBy( 'msg_id' , 'desc')
            ->first();
        if( empty($msg_obj) ){
            throw new ApiException("请先发送短信验证码");
        }
        if($msg_obj -> msg_code != $msg_code){
            throw new ApiException("验证码错误");
        }
        if($msg_obj ->expirce < time()){
            throw new ApiException("短信验证码过期");
        }
        #写入用户表
        $rand_code = rand( 1000,9999 );

        $user_model = new UserModel();
        $user_model -> phone = $phone;
        $user_model -> password = md5( $pwd .$rand_code );
        $user_model -> reg_type = $tt;
        $user_model -> ctime= time();
        $user_model -> status = 1;
        $user_model -> save();

<<<<<<< HEAD
            return $this->success();
=======
        return $this->success();
>>>>>>> master

    }
}
