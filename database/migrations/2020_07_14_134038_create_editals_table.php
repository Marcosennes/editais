<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateEditalsTable.
 */
class CreateEditalsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('editals', function(Blueprint $table) {
			$table->increments('id');
			$table->string('nome', 80);
			$table->string('arquivo', 80);
			$table->char('ano', 4);

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

		Schema::drop('editals');
	}
}
