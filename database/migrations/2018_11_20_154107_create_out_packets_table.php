<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOutPacketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('out_packets', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('gameid')->comment('分区ID');
            $table->unsignedInteger('userid')->comment('用户ID');
            $table->integer('seed_sum')->nullable()->comment('发送总额');
            $table->integer('number')->nullable()->comment('中奖数字');
            $table->integer('surplus_sum')->nullable()->comment('剩余总额');
            $table->integer('count')->nullable()->comment('红包总个数');
            $table->integer('up')->nullable()->comment('单个红包金额上限，整数，5表示为500');
            $table->integer('down')->nullable()->comment('单个红包金额下限，存储为整数，如5表示为500');
            $table->integer('surplus_count')->nullable()->comment('剩余个数');
            $table->integer('status')->nullable()->comment('状态(1-未领完，2-已领完，3-退回，4-冻结)');
            // 创建时间为 发送时间， 更新时间为 领完时间/退回时间
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
        Schema::dropIfExists('out_packets');
    }
}
