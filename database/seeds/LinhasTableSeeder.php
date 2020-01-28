<?php

use Illuminate\Database\Seeder;

class LinhasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Metro
        DB::table('linhas')->insert([
            'imagem' => '',
            'icone' => '',
            'titulo' => '',
            'slug' => 'linha1',
            'transporte_id' => 1,
            'cmsuser_id' => '1',
        ]);
        DB::table('idiomas_linhas')->insert([
            'titulo' => 'Linha 1',
            'linha_id' => 1,
            'idioma_sigla' => 'pt_BR',
            'cmsuser_id' => 1,
        ]);
        DB::table('linhas')->insert([
            'imagem' => '',
            'icone' => '',
            'titulo' => '',
            'slug' => 'linha2',
            'transporte_id' => 1,
            'cmsuser_id' => '1',
        ]);
        DB::table('idiomas_linhas')->insert([
            'titulo' => 'Linha 2',
            'linha_id' => 2,
            'idioma_sigla' => 'pt_BR',
            'cmsuser_id' => 1,
        ]);
        DB::table('linhas')->insert([
            'imagem' => '',
            'icone' => '',
            'titulo' => '',
            'slug' => 'linha3',
            'transporte_id' => 1,
            'cmsuser_id' => '1',
        ]);
        DB::table('idiomas_linhas')->insert([
            'titulo' => 'Linha 3',
            'linha_id' => 3,
            'idioma_sigla' => 'pt_BR',
            'cmsuser_id' => 1,
        ]);
        DB::table('linhas')->insert([
            'imagem' => '',
            'icone' => '',
            'titulo' => '',
            'slug' => 'linha4',
            'transporte_id' => 1,
            'cmsuser_id' => '1',
        ]);
        DB::table('idiomas_linhas')->insert([
            'titulo' => 'Linha 4',
            'linha_id' => 4,
            'idioma_sigla' => 'pt_BR',
            'cmsuser_id' => 1,
        ]);
        //BRT
        DB::table('linhas')->insert([
            'imagem' => '',
            'icone' => '',
            'titulo' => '',
            'slug' => 'carioca',
            'transporte_id' => 2,
            'cmsuser_id' => '1',
        ]);
        DB::table('idiomas_linhas')->insert([
            'titulo' => 'Carioca',
            'linha_id' => 5,
            'idioma_sigla' => 'pt_BR',
            'cmsuser_id' => 1,
        ]);
        DB::table('linhas')->insert([
            'imagem' => '',
            'icone' => '',
            'titulo' => '',
            'slug' => 'brasil',
            'transporte_id' => 2,
            'cmsuser_id' => '1',
        ]);
        DB::table('idiomas_linhas')->insert([
            'titulo' => 'Brasil',
            'linha_id' => 6,
            'idioma_sigla' => 'pt_BR',
            'cmsuser_id' => 1,
        ]);
        DB::table('linhas')->insert([
            'imagem' => '',
            'icone' => '',
            'titulo' => '',
            'slug' => 'oeste',
            'transporte_id' => 2,
            'cmsuser_id' => '1',
        ]);
        DB::table('idiomas_linhas')->insert([
            'titulo' => 'Oeste',
            'linha_id' => 7,
            'idioma_sigla' => 'pt_BR',
            'cmsuser_id' => 1,
        ]);
        DB::table('linhas')->insert([
            'imagem' => '',
            'icone' => '',
            'titulo' => '',
            'slug' => 'olimpica',
            'transporte_id' => 2,
            'cmsuser_id' => '1',
        ]);
        DB::table('idiomas_linhas')->insert([
            'titulo' => 'Olímpica',
            'linha_id' => 8,
            'idioma_sigla' => 'pt_BR',
            'cmsuser_id' => 1,
        ]);
        //VLT
        DB::table('linhas')->insert([
            'imagem' => '',
            'icone' => '',
            'titulo' => '',
            'slug' => 'linha1',
            'transporte_id' => 3,
            'cmsuser_id' => '1',
        ]);
        DB::table('idiomas_linhas')->insert([
            'titulo' => 'Linha 1',
            'linha_id' => 9,
            'idioma_sigla' => 'pt_BR',
            'cmsuser_id' => 1,
        ]);
        DB::table('linhas')->insert([
            'imagem' => '',
            'icone' => '',
            'titulo' => '',
            'slug' => 'linha2',
            'transporte_id' => 3,
            'cmsuser_id' => '1',
        ]);
        DB::table('idiomas_linhas')->insert([
            'titulo' => 'Linha 2',
            'linha_id' => 10,
            'idioma_sigla' => 'pt_BR',
            'cmsuser_id' => 1,
        ]);
        DB::table('linhas')->insert([
            'imagem' => '',
            'icone' => '',
            'titulo' => '',
            'slug' => 'linha3',
            'transporte_id' => 3,
            'cmsuser_id' => '1',
        ]);
        DB::table('idiomas_linhas')->insert([
            'titulo' => 'Linha 3',
            'linha_id' => 11,
            'idioma_sigla' => 'pt_BR',
            'cmsuser_id' => 1,
        ]);
        //Trem
        DB::table('linhas')->insert([
            'imagem' => '',
            'icone' => '',
            'titulo' => '',
            'slug' => 'santa_cruz',
            'transporte_id' => 4,
            'cmsuser_id' => '1',
        ]);
        DB::table('idiomas_linhas')->insert([
            'titulo' => 'Santa Cruz',
            'linha_id' => 12,
            'idioma_sigla' => 'pt_BR',
            'cmsuser_id' => 1,
        ]);
        DB::table('linhas')->insert([
            'imagem' => '',
            'icone' => '',
            'titulo' => '',
            'slug' => 'paracambi',
            'transporte_id' => 4,
            'cmsuser_id' => '1',
        ]);
        DB::table('idiomas_linhas')->insert([
            'titulo' => 'Paracambi',
            'linha_id' => 13,
            'idioma_sigla' => 'pt_BR',
            'cmsuser_id' => 1,
        ]);
        DB::table('linhas')->insert([
            'imagem' => '',
            'icone' => '',
            'titulo' => '',
            'slug' => 'vila_inhomirim',
            'transporte_id' => 4,
            'cmsuser_id' => '1',
        ]);
        DB::table('idiomas_linhas')->insert([
            'titulo' => 'Vila Inhomirim',
            'linha_id' => 14,
            'idioma_sigla' => 'pt_BR',
            'cmsuser_id' => 1,
        ]);
        DB::table('linhas')->insert([
            'imagem' => '',
            'icone' => '',
            'titulo' => '',
            'slug' => 'guapimirim',
            'transporte_id' => 4,
            'cmsuser_id' => '1',
        ]);
        DB::table('idiomas_linhas')->insert([
            'titulo' => 'Guapimirim',
            'linha_id' => 15,
            'idioma_sigla' => 'pt_BR',
            'cmsuser_id' => 1,
        ]);
        DB::table('linhas')->insert([
            'imagem' => '',
            'icone' => '',
            'titulo' => '',
            'slug' => 'saracuruna',
            'transporte_id' => 4,
            'cmsuser_id' => '1',
        ]);
        DB::table('idiomas_linhas')->insert([
            'titulo' => 'Saracuruna',
            'linha_id' => 16,
            'idioma_sigla' => 'pt_BR',
            'cmsuser_id' => 1,
        ]);
        DB::table('linhas')->insert([
            'imagem' => '',
            'icone' => '',
            'titulo' => '',
            'slug' => 'belford_roxo',
            'transporte_id' => 4,
            'cmsuser_id' => '1',
        ]);
        DB::table('idiomas_linhas')->insert([
            'titulo' => 'Belford Roxo',
            'linha_id' => 17,
            'idioma_sigla' => 'pt_BR',
            'cmsuser_id' => 1,
        ]);
        DB::table('linhas')->insert([
            'imagem' => '',
            'icone' => '',
            'titulo' => '',
            'slug' => 'deodoro',
            'transporte_id' => 4,
            'cmsuser_id' => '1',
        ]);
        DB::table('idiomas_linhas')->insert([
            'titulo' => 'Deodoro',
            'linha_id' => 18,
            'idioma_sigla' => 'pt_BR',
            'cmsuser_id' => 1,
        ]);
        DB::table('linhas')->insert([
            'imagem' => '',
            'icone' => '',
            'titulo' => '',
            'slug' => 'japeri',
            'transporte_id' => 4,
            'cmsuser_id' => '1',
        ]);
        DB::table('idiomas_linhas')->insert([
            'titulo' => 'Japeri',
            'linha_id' => 19,
            'idioma_sigla' => 'pt_BR',
            'cmsuser_id' => 1,
        ]);
        //Barca

        //Aeroporto

        //Bonde

        //Teleferico
        DB::table('linhas')->insert([
            'imagem' => '',
            'icone' => '',
            'titulo' => '',
            'slug' => 'alemao',
            'transporte_id' => 8,
            'cmsuser_id' => '1',
        ]);
        DB::table('idiomas_linhas')->insert([
            'titulo' => 'Alemão',
            'linha_id' => 20,
            'idioma_sigla' => 'pt_BR',
            'cmsuser_id' => 1,
        ]);
        DB::table('linhas')->insert([
            'imagem' => '',
            'icone' => '',
            'titulo' => '',
            'slug' => 'providencia',
            'transporte_id' => 8,
            'cmsuser_id' => '1',
        ]);
        DB::table('idiomas_linhas')->insert([
            'titulo' => 'Providência',
            'linha_id' => 21,
            'idioma_sigla' => 'pt_BR',
            'cmsuser_id' => 1,
        ]);
        //Bicicletario

    }
}
