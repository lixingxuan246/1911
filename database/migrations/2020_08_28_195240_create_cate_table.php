<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('news_cate', function (Blueprint $table) {


            $table->bigIncrements('cate_id')-> comment('用户id，主键');
            $table->string('cate_name',255);
            $table->string('status',255);
            $table->dateTime('ctime');
            $table->dateTime('utime');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('news_cate');
    }
}
