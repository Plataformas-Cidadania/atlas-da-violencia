<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterSettingsTable7 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->integer('posicao_mapa')->default(0);
            $table->integer('posicao_tabela')->default(0);
            $table->integer('posicao_grafico')->default(0);
            $table->integer('posicao_taxa')->default(0);
            $table->integer('posicao_metadados')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->integer('posicao_mapa')->default(0);
            $table->integer('posicao_tabela')->default(0);
            $table->integer('posicao_grafico')->default(0);
            $table->integer('posicao_taxa')->default(0);
            $table->integer('posicao_metadados')->default(0);
        });
    }
}
