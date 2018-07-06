<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTemasSeriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('temas_series', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('tema_id')->unsigned()->default(0);
            $table->foreign('tema_id')->references('id')->on('temas')->onDelete('restrict');
            $table->integer('serie_id')->unsigned()->default(0);
            $table->foreign('serie_id')->references('id')->on('series')->onDelete('cascade');
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
        Schema::drop('temas_series');
    }
}
