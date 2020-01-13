<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBusDataLineTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bus_data_line', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_transporte')->default(0);
            $table->string('linha')->nullable();
            $table->integer('qtd')->default(0);
            $table->double('velocidade_minima')->nullable();
            $table->double('velocidade_maxima')->nullable();
            $table->double('velocidade_media')->nullable();
            $table->date('data');
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
        Schema::drop('bus_data_line');
    }
}
