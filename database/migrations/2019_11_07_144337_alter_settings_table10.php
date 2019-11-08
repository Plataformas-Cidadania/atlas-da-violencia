<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterSettingsTable10 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->integer('analytics_tipo')->default(0);
            $table->string('analytics_id')->nullable();
            $table->string('analytics_url')->nullable();
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
            $table->dropColumn('analytics_tipo');
            $table->dropColumn('analytics_id');
            $table->dropColumn('analytics_url');
        });
    }
}
