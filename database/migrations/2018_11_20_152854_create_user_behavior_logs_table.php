<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserBehaviorLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_behavior_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('userid')->comment('用户id');
            $table->integer('type')->comment('类型,');
            $table->string('msg',1024)->comment('备注');
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
        Schema::dropIfExists('user_behavior_logs');
    }
}
