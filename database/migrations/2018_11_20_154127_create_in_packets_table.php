<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInPacketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('in_packets', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('outid')->comment('发出红包id');
            $table->unsignedInteger('userid')->comment('用户id');
            $table->integer('sum')->comment('红包金额，用整数表示，如4表示为40000');
            $table->integer('is_win')->comment('是否中奖，1-中奖，2-未中奖');
            $table->integer('status')->comment('状态（1-正常，2-异常）');
            // 创建时间用于领奖时间
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
        Schema::dropIfExists('in_packets');
    }
}
