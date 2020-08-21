<?php

namespace App\Http\Controllers;
use App\Exceptions\ApiException;
use App\Models\UserModel;
use Illuminate\Http\Request;

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
     * 检测收i否缺少参数
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
}
