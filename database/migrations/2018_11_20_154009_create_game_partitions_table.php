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
            $table->decimal('sum',18,4)->nullable()->comment('数量值');
            $table->decimal('up',6,2)->nullable()->comment('单个红包金额上限');
            $table->decimal('down',6,2)->nullable()->comment('单个红包金额下限');
            $table->integer('count')->nullable()->comment('发出红包红包默认可抢个数');
            $table->integer('limit')->nullable()->comment('房间内可发出红包限制数');
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
