<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIdiomasUnidadesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('idiomas_unidades', function (Blueprint $table) {
            $table->increments('id');
            $table->string('titulo');
            $table->string('idioma_sigla')->default('');
            $table->integer('unidade_id')->unsigned()->default(0);
            $table->foreign('unidade_id')->references('id')->on('unidades')->onDelete('cascade');
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
        Schema::drop('idiomas_unidades');
    }
}
