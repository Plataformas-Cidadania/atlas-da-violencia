<?php

use Illuminate\Database\Seeder;

class TransportesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Metro
        DB::table('transportes')->insert([
            'imagem' => '',
            'icone' => '',
            'titulo' => '',
            'slug' => 'metro',
            'tipo' => 2,
            'cmsuser_id' => '1',
        ]);
        DB::table('idiomas_transportes')->insert([
            'titulo' => 'Metrô',
            'transporte_id' => 1,
            'idioma_sigla' => 'pt_BR',
            'cmsuser_id' => 1,
        ]);
        //BRT
        DB::table('transportes')->insert([
            'imagem' => '',
            'icone' => '',
            'titulo' => '',
            'slug' => 'brt',
            'tipo' => 1,
            'cmsuser_id' => '1',
        ]);
        DB::table('idiomas_transportes')->insert([
            'titulo' => 'BRT',
            'transporte_id' => 2,
            'idioma_sigla' => 'pt_BR',
            'cmsuser_id' => 1,
        ]);
        //VLT
        DB::table('transportes')->insert([
            'imagem' => '',
            'icone' => '',
            'titulo' => '',
            'slug' => 'vlt',
            'tipo' => 2,
            'cmsuser_id' => '1',
        ]);
        DB::table('idiomas_transportes')->insert([
            'titulo' => 'VLT',
            'transporte_id' => 3,
            'idioma_sigla' => 'pt_BR',
            'cmsuser_id' => 1,
        ]);
        //Trem
        DB::table('transportes')->insert([
            'imagem' => '',
            'icone' => '',
            'titulo' => '',
            'slug' => 'trem',
            'tipo' => 2,
            'cmsuser_id' => '1',
        ]);
        DB::table('idiomas_transportes')->insert([
            'titulo' => 'Trem',
            'transporte_id' => 4,
            'idioma_sigla' => 'pt_BR',
            'cmsuser_id' => 1,
        ]);
        //Barca
        DB::table('transportes')->insert([
            'imagem' => '',
            'icone' => '',
            'titulo' => '',
            'slug' => 'barca',
            'tipo' => 4,
            'cmsuser_id' => '1',
        ]);
        DB::table('idiomas_transportes')->insert([
            'titulo' => 'Barca',
            'transporte_id' => 5,
            'idioma_sigla' => 'pt_BR',
            'cmsuser_id' => 1,
        ]);
        //Aeroporto
        DB::table('transportes')->insert([
            'imagem' => '',
            'icone' => '',
            'titulo' => '',
            'slug' => 'aeroporto',
            'tipo' => 3,
            'cmsuser_id' => '1',
        ]);
        DB::table('idiomas_transportes')->insert([
            'titulo' => 'Aeroporto',
            'transporte_id' => 6,
            'idioma_sigla' => 'pt_BR',
            'cmsuser_id' => 1,
        ]);
        //Bonde
        DB::table('transportes')->insert([
            'imagem' => '',
            'icone' => '',
            'titulo' => '',
            'slug' => 'bonde',
            'tipo' => 2,
            'cmsuser_id' => '1',
        ]);
        DB::table('idiomas_transportes')->insert([
            'titulo' => 'Bonde',
            'transporte_id' => 7,
            'idioma_sigla' => 'pt_BR',
            'cmsuser_id' => 1,
        ]);
        //Teleferico
        DB::table('transportes')->insert([
            'imagem' => '',
            'icone' => '',
            'titulo' => '',
            'slug' => 'teleferico',
            'tipo' => 5,
            'cmsuser_id' => '1',
        ]);
        DB::table('idiomas_transportes')->insert([
            'titulo' => 'Teleférico',
            'transporte_id' => 8,
            'idioma_sigla' => 'pt_BR',
            'cmsuser_id' => 1,
        ]);
        //Bicicletario
        DB::table('transportes')->insert([
            'imagem' => '',
            'icone' => '',
            'titulo' => '',
            'slug' => 'bicicletario',
            'tipo' => 5,
            'cmsuser_id' => '1',
        ]);
        DB::table('idiomas_transportes')->insert([
            'titulo' => 'Bicicletário',
            'transporte_id' => 9,
            'idioma_sigla' => 'pt_BR',
            'cmsuser_id' => 1,
        ]);
        //Onibus
        DB::table('transportes')->insert([
            'imagem' => '',
            'icone' => '',
            'titulo' => '',
            'slug' => 'onibus',
            'tipo' => 1,
            'cmsuser_id' => '1',
        ]);
        DB::table('idiomas_transportes')->insert([
            'titulo' => 'Ônibus',
            'transporte_id' => 10,
            'idioma_sigla' => 'pt_BR',
            'cmsuser_id' => 1,
        ]);
    }
}
