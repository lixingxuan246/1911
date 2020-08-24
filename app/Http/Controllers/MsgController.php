<?php

namespace App\Http\Controllers;
use App\models\MsgModel;
use App\Exceptions\ApiException;
use Illuminate\Http\Request;

class MsgController extends CommonController
{

    public  $msg_expirce = 60*1;
    /*
     * 发送短信验证码
     * */
    public function sendMsgCode(Request $request){

        $sid = $request -> post( 'sid' );
        $phone = $request -> post( 'phone' );
        $img_code = $request -> post( 'user_img_code' );
<<<<<<< HEAD

//        echo $img_code;
        $type = $request -> post('type');
        #验证图片验证码是否正确
        $request ->session() ->start();
        $request -> session() ->getId($sid);

        $session_code = $request -> session() ->get('img_code');

//        var_dump($session_code);die;
//        if($session_code != $img_code){
//            throw new ApiException('图片验证码不正确');
//        }else{
//            $request -> session() -> forget('img_code');
//        }
=======
//        echo $img_code;
        $type = $request -> post('type');
        #验证图片验证码是否正确
        $request -> session() ->getId($sid);
        $request ->session() ->start();
        $session_code = $request -> session() ->get('img_code');

//        var_dump($session_code);die;
        if($session_code != $img_code){
            throw new ApiException('图片验证码不正确');
        }else{
            $request -> session() -> forget('img_code');
        }
>>>>>>> master

        #发送验证码
        $where = [
            ['phone','=',$phone],
            ['type','=',$type],
        ];
        $msg_model  = new MsgModel();
        $obj = $msg_model -> where($where) ->orderBy('msg_id','desc') -> first();
<<<<<<< HEAD
//        $msg_code = rand( 100000 , 999999 );
//        if(  !empty($obj)  && $obj->expirce >time()) {
//            throw new ApiException("短信验证码发送太过频繁，请稍后重试");
//        }
=======
        $msg_code = rand( 100000 , 999999 );
        if(  !empty($obj)  && $obj->expirce >time()) {
            throw new ApiException("短信验证码发送太过频繁，请稍后重试");
        }
>>>>>>> master
        #判断是否超过10条数据
        $time = strtotime( date('Y-m-d') . '00:00:00' );
        $count_where = [
          ['phone','=',$phone],
            ['ctime' ,'>=',$time]
        ];
        if($msg_model -> where($count_where) ->count() >10){
            throw new ApiException("今天发送的太频繁了，明天再试吧");
        }

            #把数据写入数据库
            $msg_model -> phone = $phone;
            $msg_model -> type = $type;
<<<<<<< HEAD
            $msg_model -> msg_code = $img_code;
=======
            $msg_model -> msg_code = $msg_code;
>>>>>>> master
            $msg_model -> expirce = time() + $this->msg_expirce;

            $msg_model -> status = 1;
            $msg_model -> ctime = time();
            if($msg_model -> save()){
                #发送短信
<<<<<<< HEAD
                if($this->sendAliMsgCode($phone,$img_code)){
=======
                if($this->sendAliMsgCode($phone,$msg_code)){
>>>>>>> master
                return $this->success();
                }else{
                    throw new ApiException("发送失败，请重试");
                }
            }


    }
<<<<<<< HEAD

    public function oks(Request $request){
        $request -> session() -> put('hhh',123456789101112);
        $request -> session() -> save();
//
        $yy = $request-> session() -> get('hhh');
//        $kk = $request -> session() -> pull('hhh',1);

    echo $yy;

//    echo '<br>';

    }
=======
>>>>>>> master
}
