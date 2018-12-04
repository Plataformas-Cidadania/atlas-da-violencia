<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTemasConsultasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('temas_consultas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('tema_id')->unsigned()->default(0);
            $table->foreign('tema_id')->references('id')->on('temas')->onDelete('restrict');
            $table->integer('consulta_id')->unsigned()->default(0);
            $table->foreign('consulta_id')->references('id')->on('consultas')->onDelete('cascade');
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
        Schema::drop('temas_consultas');
    }
}
