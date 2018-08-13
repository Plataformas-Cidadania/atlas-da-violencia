<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSeriesTemasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('series_temas', function (Blueprint $table) {
            $table->integer('serie_id')->unsigned()->default(0);
            $table->foreign('serie_id')->references('id')->on('series')->onDelete('restrict');
            $table->integer('tema_id')->unsigned()->default(0);
            $table->foreign('tema_id')->references('id')->on('temas')->onDelete('restrict');
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
        Schema::drop('series_temas');
    }
}
