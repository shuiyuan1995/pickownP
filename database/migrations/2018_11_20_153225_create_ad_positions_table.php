<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdPositionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ad_positions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name',255)->comment('广告位名称');
            $table->integer('type')->comment('广告位类型1.图片,2.视频,3.文字');
            $table->string('intro',255)->default('')->comment('广告位简绍');
            $table->integer('is_use')->default(1)->comment('是否启用,0.不启用,1.启用');
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
        Schema::dropIfExists('ad_positions');
    }
}
