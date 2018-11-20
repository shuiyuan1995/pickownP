<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * 用户表
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name',255)->comment('用户名');
            $table->string('password',255)->comment('用户密码');
            $table->string('walletid',255)->comment('钱包地址(钱包ID)');
            $table->timestamp('last_time')->comment('上次登录时间');
            $table->integer('status')->default(1)->comment('用户状态，1-正常，2-冻结');
            // 用户注册时间用自带的创建时间表示
            $table->timestamps();
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
