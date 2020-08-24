<?php
namespace App\Http\Controllers\api;

use App\Http\Controllers\CommonController;
use App\models\NewsModel;
use Illuminate\Support\Facades\Redis;
use Illuminate\Http\Request;

//新闻 相关的接口

class NewsController extends CommonController{
    //新闻列表接口
    public function newsList(Request $request){
        //接口page和pagesize参数
        $page=$request->post('page')??1;
        $page_size=$request->post('page_size')??10;

        //拼接一下缓存的key
        $page_key='index_list_'.$page;

        $page_key.='_'.$this->getCacheVersion('news');

        //查询缓存中是否存在数据
        if($id_list=Redis::get($page_key)){

            var_dump('Redis');
            $id_arr=unserialize($id_list);
            $list=$this->getListCache($id_arr);
            return $this->success($list);
        }

        //只查询已发布的数据
        $where=[
            ['news_news.status','=',3]
        ];
        //按照发布的时间倒序
        $order_field='publish_time';
        $order_type='desc';

        $news_list_obj=NewsModel::with('getCate')
            ->where($where)
            ->orderBy($order_field,$order_type)
            ->paginate($page_size);

        if(!empty($news_list_obj)){
            foreach($news_list_obj as $k=>$v){
                $v->news_image=env('IMG_HOST').$v->news_image;
            }
        }
        $news_list=collect($news_list_obj)->toArray();
        //根据列表的数据生成 原子的缓存 按照详情数据缓存
        if(!empty($news_list)) {
            $this->buildNewsDetailCache($news_list['data']);
        }
        //把查询出来的数据生成缓存 写入redis中
         $this->buildNewsListCache($page_key,$news_list['data']);

            return $this->success($news_list['data']);
        }
        //buildNewsListCache
        public function buildNewsListCache($page_key,$news_list){
            $id_arr=array_column($news_list,'news_id');
            if(Redis::set($page_key,serialize($id_arr))){
                Redis::expire($page_key,60*5);
                return true;
            }else{
                return false;
            }
        }
    //根据列表的数据 生成详情的缓存
    public function buildNewsDetailCache($news_list){
//        $news_list = array();
//        $news_list = ;
//        dd($news_list);
        foreach($news_list as $k=>$v){
            $v['cate_name']=$v['get_cate']['cate_name'];

            $detail_key='news_detail_'.$v['news_id'];
//                                   dd($detail_key);
            Redis::hMset($detail_key,$v);
            Redis::expire($detail_key,60*5);
            return true;
        }
    }
    public function getListCache($id_arr){
        $all = [];
        foreach ( $id_arr as $k => $v) {
            $detail_key = 'news_detail_'.$v;
            $detail = Redis::hGetAll($detail_key);
            if(empty($detail)){
                $detail_obj=NewsModel::with('getCate')->find($v);
                $detail_obj ->cate_name=$detail_obj->getCate->cate_name;
                $detail = collect($detail_obj)->toArray();
                Redis::hMset($detail_key,$detail);
                $all [] = $detail;
            }else{
                $all [] = $detail;
            }
        }
        return $all;
    }
}