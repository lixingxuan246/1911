<?php

namespace App\Http\Controllers\Pc;

use App\Http\Controllers\CommonController;

use App\Exceptions\ApiException;
use Illuminate\Http\Request;
use App\Models\NewsUserModel;
use Illuminate\Support\Facades\Redis;
use App\Models\NewsModel;

class PcController extends CommonController
{
    /**
     * 登录接口
     */
    public function login(Request $request)
    {
        $phone = $this->checkApiParam('phone');
        $password = $this->checkApiParam('password');
        $tt =$this->checkApiParam('tt');
        $user_model = new NewsUserModel();
        $where =[
            ['phone','=',$phone],
        ];
        $user_obj = $user_model->where($where)->first();
        if(!$user_obj){
            throw new ApiException('用户不存在');
        }
        $this ->checkUserStatus($user_obj);
        #验证密码
        $password = md5($password.$user_obj->rand_code);
        $error_key = 'error_count_'.$phone;
        $error_count = Redis::get($error_key);
        if($error_count >= 5){
            $expire = Redis::ttl($error_key);
            if($expire < 60){
                $msg = $expire .'秒';
            }elseif($expire < 3600){
                $minutes = intval($expire / 60);
                $msg = $minutes . '分钟';
            }else{
                $hour = intval($expire / 3600);
                $minutes = intval(($expire - 3600)/ 60);
                $msg = $hour .'小时' . $minutes .'分钟';
            }
            throw new ApiException('账号已被锁定'.$msg.'后解锁');
        }
        if($password != $user_obj->password){
            if($error_count < 5){
                Redis::incr($error_key);
            }
            if($error_count == null || $error_count == 0){
                Redis::expire($error_key,60*120);
            }
            $count = $error_count +1;
            throw new ApiException('已经输错了'.$count .'次');
        }else{
            #密码输入正确 错误次数清0
            if($error_count <5 ){
                Redis::del($error_key);
            }

            #生成token
            $token = $this-> UserToken($user_obj->user_id,$tt);

            $api_response = collect($user_obj)->toArray();
            $api_response['token']=$token;
            $user_key = 'user_info_'.$user_obj->user_id;
            Redis::hmset($user_key,$api_response);
            Redis::expire($user_key,120*60);
            return $this->success($api_response);
        }
    }

    /**
     * 浏览量接口
     */
    public function clickNumber(Request $request)
    {
//        $user_id = $this->checkApiParam('user_id');
        $news_id = $this->checkApiParam('news_id');
        $where = [
            ['news_id','=',$news_id]
        ];
        $news_model = new NewsModel();

        $news_obj = $news_model->where($where)->first();
        $number = $news_obj->browse_count +1;
        $data = [
            'browse_count'=>$number
        ];
        if($news_obj){
            $news_model::where('news_id','=',$news_id)->update($data);
            return $this->success($news_obj);
        }else{
            throw new ApiException('该新闻不存在');
        }
    }
    /**
     * 点赞接口
     */
    public function clickCount(Request $request)
    {
        $user_id = $request ->post('user_id');
        $news_id = $this->checkApiParam('news_id');
        if(!$user_id){
           return $this->success('请登录','233','error');
        }else{
            $where = [
                ['news_id','=',$news_id]
            ];
            $news_model = new NewsModel();
            $obj = $news_model::where($where)->first();
            if($obj){
                $number = $obj->click_count +1;
                $data = [
                    'click_count'=>$number
                ];
                $news_model::where('news_id','=',$news_id)->update($data);
                return $this->success("点赞成功");
            }else{
                throw new ApiException('该新闻不存在');
            }
        }
    }
}
