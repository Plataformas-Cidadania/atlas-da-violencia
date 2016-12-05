<?php

use Illuminate\Database\Seeder;

class WebdoorsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('webdoors')->insert([
            'imagem' => 'banner.jpg',
            'titulo' => 'Teste 1',
            'resumida' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
            'descricao' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
            'link' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
            'cmsuser_id' => '1',
        ]);

        DB::table('webdoors')->insert([
            'imagem' => 'banner.jpg',
            'titulo' => 'Teste 2',
            'resumida' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
            'descricao' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
            'link' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
            'cmsuser_id' => '1',
        ]);

        DB::table('webdoors')->insert([
            'imagem' => 'banner.jpg',
            'titulo' => 'Teste 3',
            'resumida' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
            'descricao' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
            'link' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
            'cmsuser_id' => '1',
        ]);
    }
}
