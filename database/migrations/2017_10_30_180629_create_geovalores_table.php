<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGeovaloresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('geovalores', function (Blueprint $table) {
            $table->increments('id');
            $table->string('endereco')->nullable();
            $table->point('ponto', 'GEOMETRY', 4674);
            $table->integer('tipo')->default(0);
            $table->integer('tipo_acidente')->default(0);
            $table->integer('sexo')->default(0);
            $table->integer('faixa_etaria')->default(0);
            $table->integer('turno')->default(0);
            $table->date('data')->nullable();
            $table->time('hora')->nullable();
            $table->integer('serie_id')->unsigned();
            $table->foreign('serie_id')->references('id')->on('series')->onDelete('restrict');
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
        Schema::drop('geovalores');
    }
}
