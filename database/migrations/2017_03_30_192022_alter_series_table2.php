<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterSeriesTable2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('series', function (Blueprint $table) {
            $table->integer('indicador')->default(0); //1 - numerico 2 - taxa_por_habitantes 3 - taxa_bayesiana
            $table->integer('abrangencia')->default(0); //1 - país 2 - região 3 - uf 4 - municipio 5 - micro-região
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
            $table->dropColumn('indicador');
            $table->dropColumn('abrangencia');
        });
    }
}
