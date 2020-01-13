<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLinhasEstacoesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('linhas_estacoes', function (Blueprint $table) {
            $table->integer('estacao_id')->unsigned();
            $table->foreign('estacao_id')->references('id')->on('estacoes')->onDelete('cascade');
            $table->integer('linha_id')->unsigned();
            $table->foreign('linha_id')->references('id')->on('linhas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('linhas_estacoes');
    }
}
