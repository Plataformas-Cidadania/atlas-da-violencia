<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterSeriesTable3 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('series', function (Blueprint $table) {
            $table->dropColumn('periodicidade');
            $table->integer('periodicidade_id')->unsigned();
            $table->foreign('periodicidade_id')->references('id')->on('periodicidades')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('series', function (Blueprint $table) {
            $table->string('periodicidade');
            $table->dropForeign(['periodicidade_id']);
            $table->dropColumn('periodicidade_id');
        });
    }
}
