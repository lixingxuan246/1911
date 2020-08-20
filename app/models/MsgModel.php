<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class MsgModel extends Model
{
    public $table='news_msg';
    //设置主键  默认是id
    public $primaryKey='msg_id';

    public $timestamps=false;
}
