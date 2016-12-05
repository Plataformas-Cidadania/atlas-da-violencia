<?php

use Illuminate\Database\Seeder;

class LinksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('links')->insert([
            'imagem' => 'logo.png',
            'titulo' => 'Link titulo 1',
            'descricao' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi gravida, purus sed ultrices cursus, arcu enim mattis lorem, ut dignissim velit lacus sed erat. Curabitur at nisl a arcu varius volutpat. ',
            'link' => 'http://www.clandevelop.com',
            'posicao' => '1',
            'cmsuser_id' => '1',
        ]);

        DB::table('links')->insert([
            'imagem' => 'logo.png',
            'titulo' => 'Link titulo 2',
            'descricao' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi gravida, purus sed ultrices cursus, arcu enim mattis lorem, ut dignissim velit lacus sed erat. Curabitur at nisl a arcu varius volutpat. ',
            'link' => 'http://www.clandevelop.com',
            'posicao' => '2',
            'cmsuser_id' => '1',
        ]);

        DB::table('links')->insert([
            'imagem' => 'logo.png',
            'titulo' => 'Link titulo 3',
            'descricao' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi gravida, purus sed ultrices cursus, arcu enim mattis lorem, ut dignissim velit lacus sed erat. Curabitur at nisl a arcu varius volutpat. ',
            'link' => 'http://www.clandevelop.com',
            'posicao' => '3',
            'cmsuser_id' => '1',
        ]);

        DB::table('links')->insert([
            'imagem' => 'logo.png',
            'titulo' => 'Link titulo 4',
            'descricao' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi gravida, purus sed ultrices cursus, arcu enim mattis lorem, ut dignissim velit lacus sed erat. Curabitur at nisl a arcu varius volutpat. ',
            'link' => 'http://www.clandevelop.com',
            'posicao' => '4',
            'cmsuser_id' => '1',
        ]);

        DB::table('links')->insert([
            'imagem' => 'logo.png',
            'titulo' => 'Link titulo 5',
            'descricao' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi gravida, purus sed ultrices cursus, arcu enim mattis lorem, ut dignissim velit lacus sed erat. Curabitur at nisl a arcu varius volutpat. ',
            'link' => 'http://www.clandevelop.com',
            'posicao' => '5',
            'cmsuser_id' => '1',
        ]);

        DB::table('links')->insert([
            'imagem' => 'logo.png',
            'titulo' => 'Link titulo 6',
            'descricao' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi gravida, purus sed ultrices cursus, arcu enim mattis lorem, ut dignissim velit lacus sed erat. Curabitur at nisl a arcu varius volutpat. ',
            'link' => 'http://www.clandevelop.com',
            'posicao' => '6',
            'cmsuser_id' => '1',
        ]);

        DB::table('links')->insert([
            'imagem' => 'logo.png',
            'titulo' => 'Link titulo 7',
            'descricao' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi gravida, purus sed ultrices cursus, arcu enim mattis lorem, ut dignissim velit lacus sed erat. Curabitur at nisl a arcu varius volutpat. ',
            'link' => 'http://www.clandevelop.com',
            'posicao' => '7',
            'cmsuser_id' => '1',
        ]);

        DB::table('links')->insert([
            'imagem' => 'logo.png',
            'titulo' => 'Link titulo 8',
            'descricao' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi gravida, purus sed ultrices cursus, arcu enim mattis lorem, ut dignissim velit lacus sed erat. Curabitur at nisl a arcu varius volutpat. ',
            'link' => 'http://www.clandevelop.com',
            'posicao' => '8',
            'cmsuser_id' => '1',
        ]);
    }
}
