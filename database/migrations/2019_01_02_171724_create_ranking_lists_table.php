<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRankingListsTable extends Migration
{
    /**
     * 排行榜信息表.
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ranking_lists', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('userid')->nullable()->comment('用户id');
            $table->integer('sort')->nullable()->comment('排名');
            $table->timestamp('time')->nullable()->comment('排行榜日期');
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
        Schema::dropIfExists('ranking_lists');
    }
}
