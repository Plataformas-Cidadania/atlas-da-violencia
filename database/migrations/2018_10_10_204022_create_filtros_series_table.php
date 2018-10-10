<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFiltrosSeriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('filtros_series', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('filtro_id')->unsigned();
            $table->foreign('filtro_id')->references('id')->on('filtros')->onDelete('cascade');
            $table->integer('serie_id')->unsigned();
            $table->foreign('serie_id')->references('id')->on('series')->onDelete('cascade');
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
        Schema::drop('filtros_series');
    }
}
