<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIdiomasOptionsAbrangenciasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('idiomas_options_abrangencias', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('plural');
            $table->string('idioma_sigla')->default('');
            $table->integer('option_abrangencia_id')->unsigned();
            $table->foreign('option_abrangencia_id')->references('id')->on('options_abrangencias')->onDelete('cascade');
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
        Schema::drop('idiomas_options_abrangencias');
    }
}
