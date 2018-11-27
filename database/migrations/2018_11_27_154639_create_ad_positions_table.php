<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAdPositionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ad_positions', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name')->comment('广告位名称');
			$table->integer('type')->comment('广告位类型1.图片,2.视频,3.文字');
			$table->string('intro')->nullable()->comment('广告位简绍');
			$table->integer('is_use')->default(1)->comment('是否启用,0.不启用,1.启用');
			$table->integer('num')->nullable()->comment('轮播张数,0.为不轮播,大于0则是轮播张数按顺序来');
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
		Schema::drop('ad_positions');
	}

}
