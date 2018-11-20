<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInfoReadRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('info_read_records', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('userid')->comment('用户id');
            $table->unsignedInteger('site_mail_id')->comment('站内信id');
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
        Schema::dropIfExists('info_read_records');
    }
}
