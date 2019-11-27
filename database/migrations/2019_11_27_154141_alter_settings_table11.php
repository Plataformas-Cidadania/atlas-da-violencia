<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterSettingsTable11 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->string('h1')->nullable();
            $table->string('h2')->nullable();
            $table->string('h3')->nullable();
            $table->integer('video_home')->default(1);
            $table->integer('carousel')->default(1);
            $table->integer('links')->default(1);
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
            $table->dropColumn('h1');
            $table->dropColumn('h2');
            $table->dropColumn('h3');
            $table->dropColumn('video_home');
            $table->dropColumn('carousel');
            $table->dropColumn('links');
        });
    }
}
