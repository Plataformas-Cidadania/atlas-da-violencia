<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConsultasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('consultas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('periodicidade_id')->default(0);
            $table->integer('tema_id')->default(0);
            $table->integer('unidade_id')->default(0);
            $table->string('imagem')->default('');
            $table->string('arquivo')->default('');
            $table->string('titulo')->nullable();
            $table->string('url')->nullable();
            $table->integer('status')->default(0);
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
        Schema::drop('consultas');
    }
}
