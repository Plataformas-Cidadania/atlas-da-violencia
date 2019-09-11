<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePresentationsElementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('presentations_elements', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type'); //text, chart, image
            $table->integer('chart_type')->default(0); //1 - bar, 2 - line ...
            $table->string('language')->default('pt_BR');
            $table->text('content'); //texto, arquivo de imagem ou csv dependendo do tipo
            $table->integer('row')->default(0); //1 - 10
            $table->string('position'); //left, right, full
            $table->integer('status')->default(0);
            $table->integer('presentation_id')->unsigned();
            $table->foreign('presentation_id')->references('id')->on('presentations')->onDelete('cascade');
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
        Schema::drop('presentations_elements');
    }
}
