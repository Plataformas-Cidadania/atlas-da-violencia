<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBrtTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('brt', function (Blueprint $table) {
            $table->increments('id');
            $table->string('codigo');
            $table->string('linha')->nullable();
            $table->string('data_hora')->nullable();
            $table->double('velocidade')->nullable();
            $table->string('sentido')->nullable();
            $table->string('trajeto')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->string('id_migracao_trajeto')->nullable();
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
        Schema::drop('brt');
    }
}
