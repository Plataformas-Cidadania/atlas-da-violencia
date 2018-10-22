<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGeovaloresValoresFiltros extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('geovalores_valores_filtros', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('valor_filtro_id')->unsigned();
            $table->foreign('valor_filtro_id')->references('id')->on('valores_filtros')->onDelete('cascade');
            $table->integer('geovalor_id')->unsigned();
            $table->foreign('geovalor_id')->references('id')->on('geovalores')->onDelete('cascade');
            $table->integer('cmsuser_id')->unsigned();
            $table->foreign('cmsuser_id')->references('id')->on('cms_users')->onDelete('restrict');
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
        Schema::drop('geovalores_valores_filtros');
    }
}
