<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
	public function up()
	{
		Schema::create('users', function(Blueprint $table) {
            $table->increments('id');
			$table->string('cpf',11)->unique();
			$table->string('name', 90);			

			$table->string('email',80)->unique();
			$table->string('password',254)->nullable();

			$table->string('permission')->default('user');
			
			$table->timestamps();
		
		});
	}

	public function down()
	{
		Schema::table('users', function(Blueprint $table) {
			
		});
		Schema::drop('users');
	}
}
 