<?php

use Illuminate\Database\Seeder;

class NoticiasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('noticias')->insert([
            'imagem' => 'noticia.jpg',
            'titulo' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse placerat convallis leo at porta.',
            'descricao' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse placerat convallis leo at porta. Pellentesque blandit tortor in odio iaculis, sed luctus est sodales. Donec ultrices, ipsum id pulvinar mollis, ante massa cursus magna, vitae venenatis metus lacus a dui. Etiam tortor ipsum, vehicula vestibulum nibh id, condimentum gravida sem. Morbi gravida, purus sed ultrices cursus, arcu enim mattis lorem, ut dignissim velit lacus sed erat. Curabitur at nisl a arcu varius volutpat. Integer fringilla ipsum tellus, ullamcorper volutpat lorem suscipit ac. Suspendisse euismod nunc id dui pretium convallis ac viverra metus. Curabitur eu commodo massa. Integer aliquam orci leo.',
            'autor' => 'Relison',
            'fonte' => 'Clandevelop',
            'link_font' => 'http://www.clandevelop.com',
            'cmsuser_id' => '1',
        ]);

        DB::table('noticias')->insert([
            'imagem' => 'noticia.jpg',
            'titulo' => 'Suspendisse placerat Lorem ipsum dolor sit amet, consectetur adipiscing elit. ',
            'descricao' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse placerat convallis leo at porta. Pellentesque blandit tortor in odio iaculis, sed luctus est sodales. Donec ultrices, ipsum id pulvinar mollis, ante massa cursus magna, vitae venenatis metus lacus a dui. Etiam tortor ipsum, vehicula vestibulum nibh id, condimentum gravida sem. Morbi gravida, purus sed ultrices cursus, arcu enim mattis lorem, ut dignissim velit lacus sed erat. Curabitur at nisl a arcu varius volutpat. Integer fringilla ipsum tellus, ullamcorper volutpat lorem suscipit ac. Suspendisse euismod nunc id dui pretium convallis ac viverra metus. Curabitur eu commodo massa. Integer aliquam orci leo.',
            'autor' => 'Relison',
            'fonte' => 'Clandevelop',
            'link_font' => 'http://www.clandevelop.com',
            'cmsuser_id' => '1',
        ]);

        DB::table('noticias')->insert([
            'imagem' => 'noticia.jpg',
            'titulo' => 'Pellentesque blandit tortor in odio iaculis, sed luctus est sodales. Donec ultrices, ipsum id pulvinar mollis',
            'descricao' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse placerat convallis leo at porta. Pellentesque blandit tortor in odio iaculis, sed luctus est sodales. Donec ultrices, ipsum id pulvinar mollis, ante massa cursus magna, vitae venenatis metus lacus a dui. Etiam tortor ipsum, vehicula vestibulum nibh id, condimentum gravida sem. Morbi gravida, purus sed ultrices cursus, arcu enim mattis lorem, ut dignissim velit lacus sed erat. Curabitur at nisl a arcu varius volutpat. Integer fringilla ipsum tellus, ullamcorper volutpat lorem suscipit ac. Suspendisse euismod nunc id dui pretium convallis ac viverra metus. Curabitur eu commodo massa. Integer aliquam orci leo.',
            'autor' => 'Relison',
            'fonte' => 'Clandevelop',
            'link_font' => 'http://www.clandevelop.com',
            'cmsuser_id' => '1',
        ]);
    }
}
