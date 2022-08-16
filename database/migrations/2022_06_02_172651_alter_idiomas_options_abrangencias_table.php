<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterIdiomasOptionsAbrangenciasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('idiomas_options_abrangencias', function (Blueprint $table) {
            $table->dropForeign(['option_abrangencia_id']);
            $table->foreign('option_abrangencia_id')->references('id')->on('options_abrangencias')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('idiomas_options_abrangencias', function (Blueprint $table) {
            $table->dropForeign(['option_abrangencia_id']);
            $table->foreign('option_abrangencia_id')->references('id')->on('options_abrangencias')->onDelete('cascade');
        });
    }
}
