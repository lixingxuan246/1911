<?php

namespace App\Http\ParamMsg;
class param
{
    #中端类型没有传递
    const PARAM_MISS = [
        'tt'=> [
            'code' => 1,
            'msg'  => '终端类型不能为空'
        ],
        'phone' => [
            'code' => 2,
            'msg'  => '手机号不能为空'
        ],
        'msg_code' => [
            'code' => 3,
            'msg'  => '短信验证码不能为空'
        ],
        'password' => [
            'code' => 4,
            'msg'  => '密码不能为空'
        ],
        'img_code' => [
            'code' => 5,
            'msg'  => '图片验证码不能为空'
        ],
        'other' => [
            'code' => 1000,
            'msg'  => '缺少必要参数，请检查参数%s'
        ]
    ];
    #手机号格式不正确
    const PHONE_ERROR_CODE = 1000;
    const PHONE_ERROR_NSG = "手机号格式不正确";
}
