<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class ArticleModel extends Model
{
    /**
     *关联到模型的数据表
     **@var string
     */
    protected $table='blog_article';
    //设置主键  默认是id
    protected $primaryKey='id';
    /**
     *表名模型是否应该被打上时间戳
    表里没有created_at 和 updated_at 设为 false
     *@var bool
     */
    public $timestamps=false;
    //黑名单
    protected $guarded=[];

}
