<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEstacoesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('estacoes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('transporte_id');
            $table->string('titulo');
            $table->string('subtitulo')->nullable();
            $table->text('resumida')->nullable();
            $table->text('descricao')->nullable();
            $table->date('data_inicio')->nullable();
            $table->date('data_termino')->nullable();
            $table->string('endereco')->nullable();
            $table->string('telefone')->nullable();
            $table->integer('ativo')->default(0);
            $table->integer('status')->default(0);
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
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
        Schema::drop('estacoes');
    }
}
