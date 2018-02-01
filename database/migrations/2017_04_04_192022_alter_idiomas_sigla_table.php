<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterIdiomasSiglaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('artigos', function (Blueprint $table) {
            $table->string('idioma_sigla')->default('');
        });
        Schema::table('downloads', function (Blueprint $table) {
            $table->string('idioma_sigla')->default('');
        });
        Schema::table('indices', function (Blueprint $table) {
            $table->string('idioma_sigla')->default('');
        });
        Schema::table('links', function (Blueprint $table) {
            $table->string('idioma_sigla')->default('');
        });
        Schema::table('noticias', function (Blueprint $table) {
            $table->string('idioma_sigla')->default('');
        });
        Schema::table('quemsomos', function (Blueprint $table) {
            $table->string('idioma_sigla')->default('');
        });
        /*Schema::table('series', function (Blueprint $table) {
            $table->string('idioma_sigla')->default('');
        });*/
        Schema::table('videos', function (Blueprint $table) {
            $table->string('idioma_sigla')->default('');
        });
        Schema::table('webdoors', function (Blueprint $table) {
            $table->string('idioma_sigla')->default('');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('artigos', function (Blueprint $table) {
            $table->dropColumn('idioma_sigla');
        });
        Schema::table('downloads', function (Blueprint $table) {
            $table->dropColumn('idioma_sigla');
        });
        Schema::table('indices', function (Blueprint $table) {
            $table->dropColumn('idioma_sigla');
        });
        Schema::table('links', function (Blueprint $table) {
            $table->dropColumn('idioma_sigla');
        });
        Schema::table('noticias', function (Blueprint $table) {
            $table->dropColumn('idioma_sigla');
        });
        Schema::table('quemsomos', function (Blueprint $table) {
            $table->dropColumn('idioma_sigla');
        });
        Schema::table('series', function (Blueprint $table) {
            $table->dropColumn('idioma_sigla');
        });
        Schema::table('videos', function (Blueprint $table) {
            $table->dropColumn('idioma_sigla');
        });
        Schema::table('webdoors', function (Blueprint $table) {
            $table->dropColumn('idioma_sigla');
        });
    }
}
