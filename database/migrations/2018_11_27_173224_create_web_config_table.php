<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateWebConfigTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('web_config', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('key',255)->nullable()->comment('关键字');
			$table->string('name',255)->nullable()->comment('配置名称');
			$table->text('content', 65535)->nullable()->comment('配置内容');
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
		Schema::drop('web_config');
	}

}
