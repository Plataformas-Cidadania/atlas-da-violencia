<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIntegracoesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('integracoes', function (Blueprint $table) {
            $table->integer('estacao_id')->unsigned();
            $table->foreign('estacao_id')->references('id')->on('estacoes')->onDelete('cascade');
            $table->integer('transporte_id')->unsigned();
            $table->foreign('transporte_id')->references('id')->on('transportes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('integracoes');
    }
}
