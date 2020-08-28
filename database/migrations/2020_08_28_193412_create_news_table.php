<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('news_news', function (Blueprint $table) {


$table -> increments('news_id')->comment('用户id，主键');

            $table->string('news_title',128);
            $table->string('news_content',1000);
            $table->tinyInteger('allow_comment',false,false);
            $table->Integer('cate_id',false,false)->comment('用户id，主键');
            $table->Integer('comment_count',false,false)->comment('用户id，主键');
            $table->Integer('browse_count',false,false)->comment('用户id，主键');
            $table->Integer('click_count',false,false)->comment('用户id，主键');
            $table->Integer('publish_time',false,false)->comment('用户id，主键');
            $table->tinyInteger('status',false,false);
            $table->unsignedInteger('ctime',false,false)->comment('用户id，主键');
            $table->unsignedInteger('utime',false,false)->comment('用户id，主键');
            $table->string('news_image',256);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('news_news');
    }



}
