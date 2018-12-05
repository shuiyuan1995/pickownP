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

            $table->string('eosid',255)->nullable()->comment('区块链id');
            $table->string('blocknumber',255)->nullable()->comment('blocknumber');

            $table->decimal('income_sum',18,4)->nullable()->comment('红包金额，用整数表示，如4表示为40000');

            $table->integer('is_chailei')->nullable()->defalut(1)->comment('是否踩雷，1-未踩雷，2-踩雷');

            $table->integer('is_reward')->nullable()->defalut(1)->comment('是否中奖，1-未中奖，2-踩雷');
            $table->integer('reward_type')->nullable()->defalut(0)->comment('中奖类型,0-无，1-对子,2-三条，3-最小奖,4-整数，5-顺子，6-炸弹，7-最大奖');
            $table->decimal('reward_sum',18,4)->nullable()->defalut(0)->comment('中奖金额');
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
