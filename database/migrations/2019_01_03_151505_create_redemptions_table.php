<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRedemptionsTable extends Migration
{
    /**
     * 赎回操作表
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('redemptions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('request_id',255)->nullable()->comment('链上的请求id');
            $table->integer('userid')->nullable()->comment('用户id');
            $table->decimal('sum',18,4)->default(0)->comment('金额数');
            $table->string('coin_type',10)->default('OWN')->comment('币种');
            $table->integer('requesit_time')->nullable()->comment('赎回操作发起时间');
            $table->integer('status')->default(1)->comment('操作状态');
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
        Schema::dropIfExists('redemptions');
    }
}
