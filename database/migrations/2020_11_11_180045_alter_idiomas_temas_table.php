<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterIdiomasTemasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('idiomas_temas', function (Blueprint $table) {
            $table->string('resumida')->nullable();
            $table->text('descricao')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('idiomas_temas', function (Blueprint $table) {
            $table->dropColumn('resumida');
            $table->dropColumn('descricao');
        });
    }
}
