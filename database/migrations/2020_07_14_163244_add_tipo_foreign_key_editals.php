<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTipoForeignKeyEditals extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('editals', function (Blueprint $table) {

            $table->unsignedInteger('tipo_id');
            $table->unsignedInteger('instituicao_id');
            
			$table->foreign('tipo_id')->references('id')->on('edital_tipos');
			$table->foreign('instituicao_id')->references('id')->on('instituicaos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('editals', function (Blueprint $table) {

                $table->dropForeign('editals_tipo_id_foreign');
                $table->dropForeign('instituicaos_id_foreign');
    
            });
    }
}
