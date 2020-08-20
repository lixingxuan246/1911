<?php

namespace App\Http\Controllers\Pc;

use App\Http\Controllers\CommonController;
use App\Http\Controllers\Controller;
use App\Exceptions\ApiException;
use Illuminate\Http\Request;
use App\Models\UserModel;
use Illuminate\Support\Facades\Redis;

class NewsController extends CommonController
{
    /**
     * 登录接口
     */
    public function login(Request $request)
    {
        $phone = $this->checkApiParam('phone');
        $password = $this->checkApiParam('password');
        $tt =$this->checkApiParam('tt');
        $user_model = new UserModel();
        $where =[
            ['phone','=',$phone],
        ];
        $user_obj = $user_model->where($where)->first();
        if(!$user_obj){
            throw new ApiException('用户手机号不存在');
        }
        $this ->checkUserStatus($user_obj);
        #验证密码
        if($password==$user_obj->password){
            #生成token
            $token =  md5( uniqid() );
            $api_response = collect($user_obj)->toArray();
            $api_response['token']=$token;
//            var_dump($api_response);exit;
            $user_key = 'user_info_'.$user_obj->user_id;
            Redis::hmset($user_key,$api_response);
            Redis::expire($user_key,120);
            echo $token;
        }


    }
}
