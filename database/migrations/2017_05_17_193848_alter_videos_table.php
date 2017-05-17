<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('videos', function (Blueprint $table) {
            $table->string('descricao')->default('');
            $table->string('tags')->default('');
            $table->integer('posicao')->default(0);
            $table->string('imagem')->default('');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('videos', function (Blueprint $table) {
            $table->dropColumn('descricao');
            $table->dropColumn('tags');
            $table->dropColumn('posicao');
            $table->string('imagem');
        });
    }
}
