<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePadraoTerritoriosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('padrao_territorios', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('option_abrangencia_id')->unsigned();
            $table->foreign('option_abrangencia_id')->references('id')->on('options_abrangencias')->onDelete('cascade');
            $table->integer('cmsuser_id')->unsigned();
            $table->foreign('cmsuser_id')->references('id')->on('cms_users')->onDelete('restrict');
            $table->string('territorios');//0 - todos ou os códigos dos territorios ex: 33,34,35
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
        Schema::drop('padrao_territorios');
    }
}
