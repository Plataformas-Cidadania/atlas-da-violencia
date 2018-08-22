<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIdiomasTemasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('idiomas_temas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('titulo');
            $table->string('idioma_sigla')->default('');
            $table->integer('tema_id')->unsigned()->default(0);
            $table->foreign('tema_id')->references('id')->on('temas')->onDelete('cascade');
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
        Schema::drop('idiomas_temas');
    }
}
