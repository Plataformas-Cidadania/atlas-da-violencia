<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterMenuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('menu', function (Blueprint $table) {
            $table->string('imagem')->nullable();
            $table->string('url')->nullable();
            $table->integer('status')->default(0);
            $table->integer('posicao')->default(0);
            $table->string('idioma_sigla')->default('');
            $table->string('accesskey')->default('');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('menu', function (Blueprint $table) {
            //
        });
    }
}
