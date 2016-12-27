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
            'title' => 'O que é',
            'menu_id' => 0
        ]);
        DB::table('menu')->insert([
            'title' => 'Equipe Reponsável',
            'menu_id' => 0
        ]);
        DB::table('menu')->insert([
            'title' => 'Metadados',
            'menu_id' => 0
        ]);
        DB::table('menu')->insert([
            'title' => 'Direito de Uso',
            'menu_id' => 0
        ]);
        DB::table('menu')->insert([
            'title' => 'Dicas',
            'menu_id' => 0
        ]);
        DB::table('menu')->insert([
            'title' => 'Links',
            'menu_id' => 0
        ]);
        DB::table('menu')->insert([
            'title' => 'Fale Conosco',
            'menu_id' => 0
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
