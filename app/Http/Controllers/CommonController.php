<?php

namespace App\Http\Controllers;
use App\Exceptions\ApiException;
use App\Models\UserModel;
use App\Models\UserTokenModel;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;


class CommonController extends Controller
{
    /**
     * 接口调用成功返回的信息
     */
    public function success( $data = [], $status = 200 , $msg = 'success'){
        return [
            'status'=>$status,
            'msg' =>$msg,
            'data'=>$data
        ];
    }
    /**
     * 检测收是否缺少参数
     */
    public function checkApiParam( $key){
        $request = request();
        if(empty( $value = $request -> post($key) ) ){
            throw new ApiException('缺少参数'.$key);
        }
        return $value;
    }

    /**
     * 检测用户状态
     */
    public function checkUserStatus(UserModel $user_obj)
    {
        if($user_obj ->status == 2){
            throw new ApiException('你的账号被冻结');
        }
    }
    /**
     * 生成令牌存入数据库
     */
    private function _createUserToken($user_id ,$tt)
    {
        $token = md5( uniqid() );
        $now = time();
        $user_token_model = new UserTokenModel();
        #查询对应的中端是否登录过
        $where = [
            ['user_id','=',$user_id],
            ['tt','=',$tt],
            ['expire','>',$now]
        ];
        $user_token_obj = $user_token_model->where($where)->first();
        if(!$user_token_obj){
            $user_token_model->user_id = $user_id;
            $user_token_model->tt = $tt;
            $user_token_model->token = $token;
            $user_token_model->expire = time() + 7200;
            $user_token_model->status = 1;
            $user_token_model->ctime = time();
            $token_result = $user_token_model->save();
        }else{
            $user_token_obj->expire = time() + 7200;
            $token_result = $user_token_obj->save();
        }
        if($token_result){
            return $token;
        }else{
            throw new ApiException('令牌错误');
        }
    }
    //检测用户的令牌
    public function checkUserToken(){
        $request=request();

        $user_id=$request->post('user_id');
        $token=$request->post('token');
        $tt=$request->post('tt');

        if(empty($user_id)){
            throw new ApiException('用户id不能为空');
        }
        if(empty($token)){
            throw new ApiException('token不正确');
        }
        $token_model=new UserTokenModel();
        $where=[
            ['user_id','=',$user_id],
            ['status','=',1],
            ['tt','=',$tt]
        ];
        $token_obj=$token_model->where($where)->first();
        if(empty($token_obj)){
            throw new ApiException('还没有登录呢,请先登录',1000);
        }
        if($token_model->expire<time()){
            throw new ApiException('你需要重新登录',1000);
        }
        //验证令牌的有效期[最后一次访问之后 2小时内有效]
        $token_obj->expire=time()+7200;
        $token_obj->save();

        return true;
    }
    public function sendAliMsgCode(){
        if(env('MSG_SEND_MARK')==0){
            return true;
        }


        $host="http://smsmsgs.market.alicloudapi.com";
        $path="/dx/sendSms";
        $method="POST";
        $appcode="206b78d5d8aa4d6f8ac688a3c2096b45";
        $headers=array();
        array_push($headersm,"Authorization:APPCODE".$appcode);
        $querys='param=123456&phone=18030939917&sign=175622&skin=1';
        $bodys="";
        $url=$host.$path."?".$querys;
        $curl=curl_init();

        curl_setopt($curl,CURLOPT_CUSTOMREQUEST,$method);
        curl_setopt($curl,CURLOPT_URL,$url);
        curl_setopt($curl,CURLOPT_HTTPHEADER,$headers);
        curl_setopt($curl,CURLOPT_FAILONERROR, false);
        curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($curl,CURLOPT_HTTPHEADER,true);
        if(1==strpos("$",$host,"https://"))
        {
            curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,false);
            curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,false);
        }
        $arr=json_decode(curl_exec($curl),true);
        if($arr['return_code']=='00000'){
            return true;
        }else{
            return false;
        }
    }
    public function getCacheVersion($cache_type='news'){
        switch($cache_type){
            case 'news':
                $cache_version_key = 'news_cache_version';
                $version = Redis::get($cache_version_key);
                break;
            default:
                break;
        }
        if(empty($version)){
            Redis::set($cache_version_key,1);
            $version = 1;
        }
        return $version;
    }
}
