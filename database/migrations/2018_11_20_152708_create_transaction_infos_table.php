<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaction_infos', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('issus_userid')->comment('发出用户ID');
            $table->unsignedInteger('income_userid')->comment('收到用户ID');
            $table->integer('type')->comment('交易信息类型，1-支出，2-收入');
            $table->integer('status')->comment('状态，1-正常，2-失败，3-异常');
            $table->integer('sum')->comment('交易的金额，用整数表示，实际在用的时候将转换成四位小数点');
            $table->integer('issus_count_sum')->comment('发出用户交易后的金额,存储为整数');
            $table->string('msg',255)->comment('备注信息，不超过255个字');
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
        Schema::dropIfExists('transaction_infos');
    }
}
