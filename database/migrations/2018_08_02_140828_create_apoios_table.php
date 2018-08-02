<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApoiosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('apoios', function (Blueprint $table) {
            $table->increments('id');
            $table->string('imagem')->nullable();
            $table->string('titulo')->nullable();
            $table->text('descricao')->nullable();
            $table->string('url')->nullable();
            $table->integer('status')->default(0);
            $table->text('arquivo')->nullable();
            $table->integer('posicao')->default(0);
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
        Schema::drop('apoios');
    }
}
