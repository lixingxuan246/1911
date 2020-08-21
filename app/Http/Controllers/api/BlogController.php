<?php

namespace App\Http\Controllers\api;

use App\Exceptions\ApiException;
use App\Http\Controllers\CommonController;
use Illuminate\Http\Request;
use App\models\UserModel;
use App\models\UserTokenModel;
use App\models\ArticleModel as Article;

class BlogController extends CommonController
{
    public $expire=7200;
    //博客列表接口
    public function blogList(Request $request)
    {
        $this->checkUserToken();
        $page=$request->post('page')??1;
        $page_size = 10;
        $where = [
            ['status', '=', 2]
        ];
        $obj = Article::where($where)->paginate($page_size);
        $list = collect($obj)->toArray();
        return $this->success($list['data']);
    }
    /**
     * 登录接口
     */
    public function Login(){
        $user_info=[
            'user_id'=>1,
            'user_name'=>'zhangsan',
            'token'=>md5(uniqid()),
        ];
        return $this->success($user_info);
    }
    public function Login2(){
         $user_name=$this->checkApiParam('user_name');
         $password=$this->checkApiParam('password');
         $tt=$this->checkApiParam('tt');
       #根据用户名查询数据
        $user_model=new UserModel();
        $where=[
            ['user_name','=',$user_name],
            ['status','=',2]
        ];
        $user_obj=$user_model->where($where)->first();
        if(empty($user_obj)){
            throw new ApiException('账号密码不匹配');
        }
        //验证密码是不是正确
        $password=md5($password . $user_obj->rand);
        if($password==$user_obj->psd){
            #生成令牌
            $token=$this->_createUserToken($user_obj->id,$tt);
            $api_response=collect($user_obj)->toArray();
            $api_response['token']=$token;
            return $this->success($api_response);
        }else{
            throw new ApiException('账号密码不匹配');
        }
    }
    //给用户商城令牌 下发给用户
    public function _createUserToken($user_id,$tt){
         $token=md5(uniqid());
        $now=time();

        $user_token_model=new UserTokenModel();
        //查询对应的终端是否登录过
        $where=[
            ['user_id','=',$user_id],
            ['tt','=',$tt],
            ['expire','>',$now]
        ];
        $user_obj=$user_token_model->where($where)->first();
        if(empty($user_obj)) {
            $user_token_model->user_id = $user_id;
            $user_token_model->tt = $tt;
            $user_token_model->token = $token;
            $user_token_model->expire = $now + $this->expire;
            $user_token_model->status = 1;
            $user_token_model->ctime = $now;
            $token_result=$user_token_model->save();
        }else{
            $user_token_obj->expire=$now+$this->expire;
            $token_result=$user_token_obj->save();


        }
    }
    public function getImageCodeUrl(Request $request){
        $request->session()->start();
        $sid=$request->session()->getSid();
        $arr['url']='http://api3.mazhanliang.top/showImageCode?sid='.$sid;
        $arr['sid'] = $sid;
        return $this->success($arr);
    }

    //test
    public function showImageCode(Request $request){
//        $sid=$request->get('sid');
//        if(empty($sid)){
//            throw new ApiException('图片验证码输出失败');
//        }
//        $request->session()->setid($sid);
//        $request->session()->start();

        header('Content-Type: image/png');

// Create the image
        $im = imagecreatetruecolor(100, 30);

        // Create some colors
        $white = imagecolorallocate($im, 255, 255, 255);
        $black = imagecolorallocate($im, 0, 0, 0);
        imagefilledrectangle($im, 0, 0, 399, 29, $white);
        $grey=imagecolorallocate($im,128,128,128);
        // The text to draw
        $text = ''.rand(1000,9999);
        $request->session()->put('img_code',$text);
        $request->session()->save();
        // Replace path by your own font path
        $font =storage_path().'/comic.ttf';

        // Add some shadow to the text
        $i=0;
        while($i<strlen($text)){
            imageline($im,rand(0,10),rand(0,25),rand(90,100),rand(10,25),$grey);
            imagettftext($im, 20, rand(-15,15), 11+20*$i, 21, $black, $font, $text[$i]);
            $i++;
        }

        // Using imagepng() results in clearer text compared with imagejpeg()
        imagepng($im);
        imagedestroy($im);
        exit;
    }
}
