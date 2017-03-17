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
            $table->unsignedSmallInteger('tipo_regiao')->default('0');//1 - região (sudeste...) 2 - uf 3 - municipio 4 - microregiao 5 - mesoregiao
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
