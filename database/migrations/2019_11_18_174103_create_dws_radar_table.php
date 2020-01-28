<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDwsRadarTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dws_radar', function (Blueprint $table) {
            $table->increments('id');
            $table->string('data_hora');
            $table->integer('radar_id_fonte')->default(0);
            $table->integer('tipo')->default(0);
            $table->integer('status')->default(0);
            $table->double('velocidade')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->string('sentido_duplo')->nullable();
            $table->string('sentido_todos')->nullable();
            $table->string('direcao_real')->nullable();
            $table->string('sigla_rodovia')->nullable();
            $table->string('km_rodovia')->nullable();
            $table->string('pais')->nullable();
            $table->string('uf')->nullable();
            $table->string('cod_municipio')->nullable();
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
        Schema::drop('dws_radar');
    }
}
