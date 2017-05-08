<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterQuemsomosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quemsomos', function (Blueprint $table) {
            $table->integer('origem_id')->default(0);
            $table->integer('posicao')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quemsomos', function (Blueprint $table) {
            //
        });
    }
}
