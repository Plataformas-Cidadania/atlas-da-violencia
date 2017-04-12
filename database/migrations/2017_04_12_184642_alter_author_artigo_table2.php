<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAuthorArtigoTable2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('author_artigo', function (Blueprint $table) {
            $table->dropForeign(['artigo_id']);
            $table->foreign('artigo_id')->references('id')->on('artigos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('author_artigo', function (Blueprint $table) {
            $table->dropForeign(['artigo_id']);
            $table->foreign('artigo_id')->references('id')->on('artigos')->onDelete('restrict');
        });
    }
}
