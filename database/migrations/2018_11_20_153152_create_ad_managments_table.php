<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdManagmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ad_managments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name',128)->nullable()->comment('广告名称');
            $table->unsignedInteger('ad_id')->nullable()->comment('广告位id');
            $table->string('img_url',1024)->nullable()->comment('图片链接地址');
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
        Schema::dropIfExists('ad_managments');
    }
}
