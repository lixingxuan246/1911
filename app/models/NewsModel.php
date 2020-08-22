<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class NewsModel extends Model
{
    public $table='news_news';
    //设置主键  默认是id
    public $primaryKey='news_id';

    public $timestamps=false;

    public function getCate(){
        return $this->hasOne('App\models\CateModel','cate_id','cate_id');
    }
}
