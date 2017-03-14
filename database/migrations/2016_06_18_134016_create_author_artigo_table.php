<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuthorArtigoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
 */
    public function up()
    {
        Schema::create('author_artigo', function (Blueprint $table) {
            $table->integer('author_id')->unsigned();
            $table->foreign('author_id')->references('id')->on('authors')->onDelete('restrict');
            $table->integer('artigo_id')->unsigned();
            $table->foreign('artigo_id')->references('id')->on('artigos')->onDelete('restrict');
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
        Schema::drop('author_artigo');
    }
}
