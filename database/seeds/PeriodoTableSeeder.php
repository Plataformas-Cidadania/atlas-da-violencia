<?php

use Illuminate\Database\Seeder;

class PeriodoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('periodos')->insert([
            'titulo' => 'Anual',
            'cmsuser_id' => 1
        ]);
        DB::table('periodos')->insert([
            'titulo' => 'Mensal',
            'cmsuser_id' => 1
        ]);
        DB::table('periodos')->insert([
            'titulo' => 'Trimestral',
            'cmsuser_id' => 1
        ]);
        DB::table('periodos')->insert([
            'titulo' => 'Semestral',
            'cmsuser_id' => 1
        ]);
    }
}
