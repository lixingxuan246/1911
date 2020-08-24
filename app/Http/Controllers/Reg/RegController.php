<?php

namespace App\Http\Controllers\Reg;

use Illuminate\Http\Request;
use AlibabaCloud\Client\AlibabaCloud;
use App\Exceptions\ApiException;
use AlibabaCloud\Client\Exception\ClientException;
use AlibabaCloud\Client\Exception\ServerException;

use App\models\MsgModel;
use App\models\UserModel;

class RegController extends CommonController
{
//短信验证码
    public function sendMsgCode()
    {

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
        if (1 == strpos("$" . $host, "https://")) {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        }
        var_dump(curl_exec($curl));
    }

    public function showImageCode()
    {

        $request = request();
        $sid = $request->get('sid');
        if (empty($sid)) {
            throw new ApiException("图片验证码输入失败");
        }

        $request->session()->setid('sid');
        $request->session()->start();


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
        $text = '' . rand(1000, 9999);
        $request->session()->put('img_code', $text);

        $request->session()->save();
//        $session->save();
// Replace path by your own font path
        $font = storage_path() . '/comic.ttf';

// Add some shadow to the text
        $i = 0;
        while ($i < strlen($text)) {
            imageline($im, rand(0, 10), rand(0, 25), rand(90, 100), rand(10, 25), rand(10, 25));
            imagettftext($im, 20, rand(-15, 15), 11 + 20 * $i, 21, $grey, $font, $text[$i]);
            $i++;
        }


// Add the text
//        imagettftext($im, 20, 0, 10, 20, $black, $font, $text);

// Using imagepng() results in clearer text compared with imagejpeg()
        imagepng($im);
        imagedestroy($im);
        exit;
    }

//图片验证码
    public function getImageCodeUrl(Request $request)
    {
        $request->session()->start();
        $sid = $request->session()->getId();
        $arr['url'] = 'http://api3.mazhanliang.top/showImageCode?sid=' . $sid;
        $arr['sid'] = $sid;

        return $this->success($arr);


//        var_dump($sid);
    }

    /*
     * 注册接口
     * */
    public function reg(Request $request)
    {
        $tt = $request->post('tt');
        $phone = $request->post('phone');

        $msg_code = $request->post('user_code');
        $pwd = $request->post('pwd');


        #验证短信验证码 1，验证短信验证码是否过期 2，验证码是否正确
        $msg_model = new MsgModel();
        $where = [
            ['phone', '=', $phone],
            ['type', '=', 1]
        ];
        $msg_obj = $msg_model->where($where)
            ->orderBy('msg_id', 'desc')
            ->first();
        if (empty($msg_obj)) {
            throw new ApiException("请先发送短信验证码");
        }
        if ($msg_obj->msg_code != $msg_code) {
            throw new ApiException("验证码错误");
        }
        if ($msg_obj->expirce < time()) {
            throw new ApiException("短信验证码过期");
        }
        #写入用户表
        $rand_code = rand(1000, 9999);

        $user_model = new UserModel();
        $user_model->phone = $phone;
        $user_model->password = md5($pwd . $rand_code);
        $user_model->reg_type = $tt;
        $user_model->ctime = time();
        $user_model->status = 1;
        $user_model->save();

        return $this->success();
    }
}
