<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIdiomasLinhasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('idiomas_linhas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('titulo');
            $table->string('idioma_sigla')->default('');
            $table->integer('linha_id')->unsigned()->default(0);
            $table->foreign('linha_id')->references('id')->on('linhas')->onDelete('cascade');
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
        Schema::drop('idiomas_linhas');
    }
}
