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
        DB::table('periodicidades')->insert([
            'titulo' => 'Anual',
            'cmsuser_id' => 1,
            'idioma_sigla' => 'pt_BR'
        ]);
        DB::table('periodicidades')->insert([
            'titulo' => 'Mensal',
            'cmsuser_id' => 1,
            'idioma_sigla' => 'pt_BR'
        ]);
        DB::table('periodicidades')->insert([
            'titulo' => 'Trimestral',
            'cmsuser_id' => 1,
            'idioma_sigla' => 'pt_BR'
        ]);
        DB::table('periodicidades')->insert([
            'titulo' => 'Semestral',
            'cmsuser_id' => 1,
            'idioma_sigla' => 'pt_BR'
        ]);
    }
}
