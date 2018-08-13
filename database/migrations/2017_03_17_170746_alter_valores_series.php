<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterValoresSeries extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('valores_series', function (Blueprint $table) {
            //$table->unsignedSmallInteger('tipo_regiao')->default('0');//1 - região (sudeste...) 2 - uf 3 - municipio 4 - microregiao 5 - mesoregiao
            $table->unsignedSmallInteger('tipo_regiao')->default('0');//1 - pais 2 - regiao 3 - uf 4 - municipio 5 - micro-regiao 6 - meso-regiao
            $table->integer('regiao_id')->default('0');
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
            $table->dropColumn('tipo_regiao');
            $table->dropColumn('regiao_id');
        });
    }
}
