<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use AlibabaCloud\Client\AlibabaCloud;
use App\Exceptions\ApiException;
use AlibabaCloud\Client\Exception\ClientException;
use AlibabaCloud\Client\Exception\ServerException;
use DB;
class LoginController extends CommonController
{
    /*
     * 登录接口
     * */

    public function login(){
        $request = request();
        $phone = $request->post('phone');
        $yzm = $request->post('yzm');
        $res = $this->sendSms($phone,$yzm);
        if($res['Code']!=='OK'){
            session(['code'=>$yzm]);
            request()->session()->save();
            echo json_encode(['code'=>'00000','msg'=>'ok']);die;
        }
        echo json_encode(['code'=>'00001','msg'=>'发送失败']);die;
    }

    public function aa(){
        $data = DB::table('tital')->select('*')->get()->toArray();
        return $data;
    }

    public function sendMsgCode(){

        $host = "http://dingxin.market.alicloudapi.com";
        $path = "/dx/sendSms";
        $method = "POST";
        $appcode = "你自己的AppCode";
        $headers = array();
        array_push($headers, "Authorization:APPCODE " . $appcode);
        $querys = "mobile=159xxxx9999&param=code%3A1234&tpl_id=TP1711063";
        $bodys = "";
        $url = $host . $path . "?" . $querys;

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_FAILONERROR, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, true);
        if (1 == strpos("$".$host, "https://"))
        {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        }
        var_dump(curl_exec($curl));
    }

    public function showImageCode(Request $request){


        $sid = $request->get('sid');
        if( empty($sid) ){
            throw new ApiException("图片验证码输入失败");
        }

        $request -> session() ->  setid('sid');
        $request -> session() -> start();


// Set the content-type
        header('Content-Type: image/png');

// Create the image
        $im = imagecreatetruecolor(100, 30);

// Create some colors
        $white = imagecolorallocate($im, 255, 255, 255);
        $grey = imagecolorallocate($im, 128, 128, 128);
        $black = imagecolorallocate($im, 0, 0, 0);
        imagefilledrectangle($im, 0, 0, 399, 29, $white);

// The text to draw
        $text = ''.rand(1000,9999);
        $request -> session() -> put('img_code',$text);

        $request -> session() ->save();

// Replace path by your own font path
        $font = storage_path().'/comic.ttf';

// Add some shadow to the text
        $i = 0;
        while( $i< strlen($text) ){
            imageline($im,rand(0,10),rand(0,25),rand(90,100),rand(10,25),rand(10,25));
            imagettftext($im, 20, rand(-15,15), 11+20*$i, 21, $grey, $font, $text[$i]);
            $i++;
        }


// Add the text
//        imagettftext($im, 20, 0, 10, 20, $black, $font, $text);

// Using imagepng() results in clearer text compared with imagejpeg()
        imagepng($im);
        imagedestroy($im);
        exit;
    }

    public function test23(){
        phpinfo();
    }



    public function getImageCodeUrl(Request $request){
        $request -> session() -> start();
        $sid = $request -> session() -> getId();
        $arr['url'] = 'http://api3.mazhanliang.top/showImageCode?sid='.$sid;
        $arr['sid'] = $sid;

         return $this->success($arr);



//        var_dump($sid);
    }

}
