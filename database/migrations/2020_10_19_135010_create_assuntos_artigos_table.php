<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssuntosArtigosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assuntos_artigos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('assunto_id')->unsigned()->default(0);
            $table->foreign('assunto_id')->references('id')->on('assuntos')->onDelete('cascade');
            $table->integer('artigo_id')->unsigned()->default(0);
            $table->foreign('artigo_id')->references('id')->on('artigos')->onDelete('cascade');
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
        Schema::drop('assuntos_artigos');
    }
}
