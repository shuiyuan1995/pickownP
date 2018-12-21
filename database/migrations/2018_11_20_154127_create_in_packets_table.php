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

            $table->integer('is_chailei')->nullable()->defalut(2)->comment('是否踩雷，1-踩雷，2-未踩雷');

            $table->integer('is_reward')->nullable()->defalut(1)->comment('是否中奖，1-未中奖，2-踩雷');
            $table->integer('reward_type')->nullable()->defalut(0)->comment('中奖类型,0-无，1-对子,2-三条,3-整数，4-顺子，5-炸弹');
            $table->decimal('reward_sum',18,4)->nullable()->defalut(0)->comment('中奖金额');
            $table->string('addr',255)->nullable()->comment();
            $table->decimal('own',18,4)->nullable()->comment('挖矿数量');
            $table->decimal('prize_pool',18,4)->nullable()->commene('当前奖池的数量');
            $table->string('txid',255)->nullable()->comment('抢红包的唯一标志');
            $table->decimal('reffee',18,4)->nullable()->default(0)->comment('邀请金额');
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
