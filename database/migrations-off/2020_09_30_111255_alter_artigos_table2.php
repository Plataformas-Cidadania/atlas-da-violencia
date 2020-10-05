<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterArtigosTable2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('artigos', function (Blueprint $table) {
            $table->date('data')->nullable();
            $table->integer('fonte_id')->default(0); // 0 - Selecione | 1 - Atlas Violência | 2 - Outros
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
            $table->dropColumn('data');
            $table->dropColumn('fonte_id');
        });
    }
}
