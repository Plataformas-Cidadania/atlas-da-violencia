<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTextosSeriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('textos_series', function (Blueprint $table) {
            $table->increments('id');
            $table->string('titulo');
            $table->text('descricao');
            $table->string('idioma_sigla')->default('');
            $table->integer('serie_id')->unsigned()->default(0);
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
        Schema::drop('textos_series');
    }
}
