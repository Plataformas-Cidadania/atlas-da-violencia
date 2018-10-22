<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterValoresFiltrosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('valores_filtros', function (Blueprint $table) {
            $table->string('imagem')->nullable();
            $table->integer('tipo_grafico')->default(0);
            $table->integer('mostrar_grafico')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('valores_filtros', function (Blueprint $table) {
            $table->dropColumn('imagem');
            $table->dropColumn('tipo_grafico');
            $table->dropColumn('mostrar_grafico');
        });
    }
}
