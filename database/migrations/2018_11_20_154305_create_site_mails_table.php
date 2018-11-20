<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSiteMailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('site_mails', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('type')->comment('类型，1-消息，2-公告');
            $table->Integer('userid')->comment('用户id，类型为公告时为0');
            $table->string('title',255)->comment('标题');
            $table->string('content',1024)->comment('内容');
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
        Schema::dropIfExists('site_mails');
    }
}
