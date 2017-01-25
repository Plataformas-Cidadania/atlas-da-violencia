<?php

use Illuminate\Database\Seeder;

class IndicesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('indices')->insert([
            'imagem' => '',
            'titulo' => 'Índices 1',
            'valor' => '111111',
            'posicao' => '1',
            'status' => '1',
            'cmsuser_id' => '1',
        ]);
        DB::table('indices')->insert([
            'imagem' => '',
            'titulo' => 'Índices 2',
            'valor' => '22222',
            'posicao' => '2',
            'status' => '1',
            'cmsuser_id' => '1',
        ]);
        DB::table('indices')->insert([
            'imagem' => '',
            'titulo' => 'Índices 3',
            'valor' => '333333',
            'posicao' => '3',
            'status' => '1',
            'cmsuser_id' => '1',
        ]);
        DB::table('indices')->insert([
            'imagem' => '',
            'titulo' => 'Índices 4',
            'valor' => '333333',
            'posicao' => '4',
            'status' => '1',
            'cmsuser_id' => '1',
        ]);
    }
}
