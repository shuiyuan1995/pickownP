<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRewardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rewards', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('userid')->comment('用户id');
            $table->decimal('pairs', 18, 4)->nullable()->default(0)->comment('对子数');
            $table->decimal('three', 18, 4)->nullable()->default(0)->comment('三条数');
            $table->decimal('min', 18, 4)->nullable()->default(0)->comment('最小数');
            $table->decimal('int', 18, 4)->nullable()->default(0)->comment('整数');
            $table->decimal('shunzi', 18, 4)->nullable()->default(0)->comment('顺子');
            $table->decimal('bomb', 18, 4)->nullable()->default(0)->comment('炸弹');
            $table->decimal('max', 18, 4)->nullable()->default(0)->comment('最大数');
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
        Schema::dropIfExists('rewards');
    }
}
