<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserInfosTable extends Migration
{
    /**
     * 用户信息表
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_infos', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('userid')->comment('关联users.id');
            $table->string('email',255)->nullable()->comment('邮箱');
            $table->string('phone',255)->nullable()->comment('手机号');
            $table->string('referral_code',255)->nullable()->comment('推荐码');
            $table->string('invite_id',255)->nullable()->comment('邀请人id');
            $table->integer('in_packet_eos')->nullable()->comment('用户抢红包累积金额');
            $table->integer('out_packet_eos')->nullable()->comment('用户发红包累积金额');
            $table->integer('reward_eos')->nullable()->comment('用户奖励的EOS');
            $table->integer('punish_eos')->nullable()->comment('用户被惩罚的EOS');
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
        Schema::dropIfExists('user_infos');
    }
}
