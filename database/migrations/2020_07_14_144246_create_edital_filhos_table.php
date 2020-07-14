<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateEditalFilhosTable.
 */
class CreateEditalFilhosTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('edital_filhos', function(Blueprint $table) {
			$table->increments('id');
			$table->string('nome', 80);
			$table->string('endereco', 80);
			$table->unsignedInteger('pai_id');

			$table->timestamps();
			
			$table->foreign('pai_id')->references('id')->on('editals');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('edital_filhos', function(Blueprint $table) {

			$table->dropForeign('edital_filhos_pai_id_foreign');

		});

		Schema::drop('edital_filhos');
	}
}
