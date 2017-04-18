<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSeries extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('series', function (Blueprint $table) {
            $table->increments('id');
            $table->string('imagem');
            $table->string('titulo');
            $table->string('periodicidade');
            $table->text('descricao');
            //VERIFICAR POSSÍVEL EXCLUSÃO DA COLUNA TIPO VALORES
            $table->integer('tipo_valores');//1 - Numérico Incremental / 2 - Numérico Agregado / 3 - Taxa
            $table->integer('serie_id')->default(0);//utiliza para criar relações entre séries
            $table->integer('tema_id')->unsigned();
            $table->foreign('tema_id')->references('id')->on('temas')->onDelete('restrict');
            $table->integer('fonte_id')->unsigned();
            $table->foreign('fonte_id')->references('id')->on('fontes')->onDelete('restrict');
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
        Schema::drop('series');
    }
}
