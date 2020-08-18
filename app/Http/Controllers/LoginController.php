<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use AlibabaCloud\Client\AlibabaCloud;

use AlibabaCloud\Client\Exception\ClientException;
use AlibabaCloud\Client\Exception\ServerException;
class LoginController extends Controller
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


    public function sendSms($mobile,$code){
        AlibabaCloud::accessKeyClient('LTAI4Fn3dy5uP4XWA1AJnaRC', 'tSxDFdkwUG0EVtJ7GTcY24Y2mUf85V')
            ->regionId('cn-hangzhou')
            ->asDefaultClient();

        try {
            $result = AlibabaCloud::rpc()
                ->product('Dysmsapi')
                // ->scheme('https') // https | http
                ->version('2017-05-25')
                ->action('SendSms')
                ->method('POST')
                ->host('dysmsapi.aliyuncs.com')
                ->options([
                    'query' => [
                        'RegionId' => "cn-hangzhou",
                        'PhoneNumbers' => $mobile,
                        'SignName' => "小星",
                        'TemplateCode' => "SMS_181200828",
                        'TemplateParam' => "{code:$code}",
                    ],
                ])
                ->request();
            return $result->toArray();
        } catch (ClientException $e) {
            return $e->getErrorMessage();
        } catch (ServerException $e) {
            return $e->getErrorMessage();
        }

    }

    public function showImageCode(){
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
// Replace path by your own font path
        $font = storage_path().'comic.ttf';

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
    }

    public function test23(){
        phpinfo();
    }


    public function getImageCodeUrl(Request $request){
        $request -> session() -> start();
        $sid = $request -> session() -> getId();
        $arr['url'] = 'http://api3.mazhanliang.top/showImageCode';
        $arr['sid'] = $sid;

        

//        var_dump($sid);
    }

}
