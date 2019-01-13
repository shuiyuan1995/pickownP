<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRankingListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ranking_lists', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('userid')->nullable()->comment('用户id');
            $table->decimal('balance',18,4)->nullable()->comment('发红包的金额');
            $table->decimal('prize',18,4)->nullable()->comment('奖励金额');
            $table->integer('ranking')->nullable()->comment('名次');
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
