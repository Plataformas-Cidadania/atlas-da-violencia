<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterIdiomasIdTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('artigos', function (Blueprint $table) {
            $table->integer('idioma_id')->default(0);
        });
        Schema::table('downloads', function (Blueprint $table) {
            $table->integer('idioma_id')->default(0);
        });
        Schema::table('indices', function (Blueprint $table) {
            $table->integer('idioma_id')->default(0);
        });
        Schema::table('links', function (Blueprint $table) {
            $table->integer('idioma_id')->default(0);
        });
        Schema::table('noticias', function (Blueprint $table) {
            $table->integer('idioma_id')->default(0);
        });
        Schema::table('quemsomos', function (Blueprint $table) {
            $table->integer('idioma_id')->default(0);
        });
        Schema::table('series', function (Blueprint $table) {
            $table->integer('idioma_id')->default(0);
        });
        Schema::table('videos', function (Blueprint $table) {
            $table->integer('idioma_id')->default(0);
        });
        Schema::table('webdoors', function (Blueprint $table) {
            $table->integer('idioma_id')->default(0);
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
            $table->dropColumn('idioma_id')->default(0);
        });
        Schema::table('downloads', function (Blueprint $table) {
            $table->dropColumn('idioma_id')->default(0);
        });
        Schema::table('indices', function (Blueprint $table) {
            $table->dropColumn('idioma_id')->default(0);
        });
        Schema::table('links', function (Blueprint $table) {
            $table->dropColumn('idioma_id')->default(0);
        });
        Schema::table('noticias', function (Blueprint $table) {
            $table->dropColumn('idioma_id')->default(0);
        });
        Schema::table('quemsomos', function (Blueprint $table) {
            $table->dropColumn('idioma_id')->default(0);
        });
        Schema::table('series', function (Blueprint $table) {
            $table->dropColumn('idioma_id')->default(0);
        });
        Schema::table('videos', function (Blueprint $table) {
            $table->dropColumn('idioma_id')->default(0);
        });
        Schema::table('webdoors', function (Blueprint $table) {
            $table->dropColumn('idioma_id')->default(0);
        });
    }
}
