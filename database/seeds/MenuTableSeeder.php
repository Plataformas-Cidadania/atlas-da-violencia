<?php

use Illuminate\Database\Seeder;

class MenuTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('menu')->insert([
            'title' => 'O que é'
        ]);
        DB::table('menu')->insert([
            'title' => 'Equipe Reponsável'
        ]);
        DB::table('menu')->insert([
            'title' => 'Metadados'
        ]);
        DB::table('menu')->insert([
            'title' => 'Direito de Uso'
        ]);
        DB::table('menu')->insert([
            'title' => 'Dicas'
        ]);
        DB::table('menu')->insert([
            'title' => 'Links'
        ]);
        DB::table('menu')->insert([
            'title' => 'Fale Conosco'
        ]);



        DB::table('menu')->insert([
            'title' => 'aaaa',
            'menu_id' => 2
        ]);
        DB::table('menu')->insert([
            'title' => 'bbbb',
            'menu_id' => 2
        ]);
        DB::table('menu')->insert([
            'title' => 'ccc',
            'menu_id' => 2
        ]);
        DB::table('menu')->insert([
            'title' => 'dddd',
            'menu_id' => 2
        ]);

        DB::table('menu')->insert([
            'title' => 'eeee',
            'menu_id' => 9
        ]);
        DB::table('menu')->insert([
            'title' => 'ffff',
            'menu_id' => 9
        ]);
        DB::table('menu')->insert([
            'title' => 'gggg',
            'menu_id' => 9
        ]);


        DB::table('menu')->insert([
            'title' => 'ffff',
            'menu_id' => 12
        ]);
        DB::table('menu')->insert([
            'title' => 'gggg',
            'menu_id' => 12
        ]);
    }
}
