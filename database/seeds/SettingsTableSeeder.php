<?php

use Illuminate\Database\Seeder;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('settings')->insert([
            'imagem' => 'logo.png',
            'email' => 'atendimento@cd10.com.br',
            'titulo' => 'Nome Site',
            'rodape' => 'Todos direitos Reservados',
            'cep' => '24456-422',
            'endereco' => 'Av. Domingos Damasceno Duarte',
            'numero' => '866',
            'complemento' => '',
            'bairro' => 'Trindade',
            'cidade' => 'São Gonçalo',
            'estado' => 'RJ',
            'titulo_contato' => 'Titulo Contato',
            'descricao_contato' => 'Descrição Contato',
            'telefone' => '(21) 3248-1143',
            'telefone2' => '(21) 9-8681-8735',
            'telefone3' => '',
            'facebook' => '#',
            'youtube' => '#',
            'pinterest' => '#',
            'twitter' => '#',
            'cmsuser_id' => '1',
            'padrao_abrangencia' => '3,2,1,4'
        ]);
    }
}
