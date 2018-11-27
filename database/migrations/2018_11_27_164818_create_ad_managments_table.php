<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAdManagmentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ad_managments', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name', 128)->nullable()->comment('广告名称');
			$table->integer('ad_id')->unsigned()->nullable()->comment('广告位id');
			$table->integer('type')->nullable()->comment('类型');
			$table->text('img_url', 65535)->nullable()->comment('图片链接地址');
			$table->integer('sort')->nullable()->default(0)->comment('排序');
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
		Schema::drop('ad_managments');
	}

}
