<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGamePartitionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('game_partitions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name',255)->nullable()->comment('游戏分区名');
            $table->integer('sum')->nullable()->comment('数量值');
            $table->integer('up')->nullable()->comment('单个红包金额上限，存储为整数，如2表示为200');
            $table->integer('down')->nullable()->comment('单个红包金额下限，存储为整数，如5表示为500');
            $table->integer('count')->nullable()->comment('默认拆分个数');
            $table->integer('number')->nullable()->comment('默认尾数');
            $table->integer('status')->nullable()->comment('状态，1-开启，2-关闭');
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
        Schema::dropIfExists('game_partitions');
    }
}
