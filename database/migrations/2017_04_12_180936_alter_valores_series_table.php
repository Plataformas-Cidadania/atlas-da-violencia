<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterValoresSeriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('valores_series', function (Blueprint $table) {
            $table->dropForeign(['serie_id']);
            $table->foreign('serie_id')->references('id')->on('series')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('valores_series', function (Blueprint $table) {
            $table->dropForeign(['serie_id']);
            $table->foreign('serie_id')->references('id')->on('series')->onDelete('restrict');
        });
    }
}
