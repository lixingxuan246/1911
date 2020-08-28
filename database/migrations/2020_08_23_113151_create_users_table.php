<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            #设置表引擎
            $table->engine = 'InnoDB';
            #字符集
            $table->charset = 'utf8';
            #校对
            $table->collation = 'utf8_croatian_ci';

            $table->bigInteger('id')->comment('用户id，主键');
            $table->dateTime('create_at');
            $table->dateTime('update_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
