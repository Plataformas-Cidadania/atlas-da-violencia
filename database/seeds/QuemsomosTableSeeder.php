<?php

use Illuminate\Database\Seeder;

class QuemsomosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('quemsomos')->insert([
            'imagem' => 'logo.png',
            'tipo' => '0',
            'titulo' => 'Bem vindos',
            'descricao' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi gravida, purus sed ultrices cursus, arcu enim mattis lorem, ut dignissim velit lacus sed erat. Curabitur at nisl a arcu varius volutpat. ',
            'cmsuser_id' => '1',
        ]);

        DB::table('quemsomos')->insert([
            'imagem' => 'logo.png',
            'tipo' => '1',
            'titulo' => 'Quem Somos',
            'descricao' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras eu libero in ex maximus hendrerit. Duis auctor egestas tempor. Duis eget hendrerit odio. Nam ut metus accumsan, porta felis eget, tristique nisl. Donec malesuada in ligula ut mollis. Vivamus scelerisque arcu at volutpat iaculis. Cras efficitur volutpat rutrum. Etiam mattis hendrerit pellentesque. Sed tellus orci, lacinia ac ullamcorper vitae, dictum eu neque. Maecenas odio magna, laoreet ac est vitae, convallis ultricies turpis. Phasellus blandit ornare odio, congue imperdiet magna feugiat ac. Integer vulputate lacinia urna quis varius. Nulla posuere dapibus facilisis. Fusce ut rutrum ipsum, at sollicitudin arcu. Morbi vitae erat a est mattis commodo. Nunc ex magna, pharetra a consectetur in, ultrices eu tellus.

Nulla condimentum sapien ac gravida tincidunt. Vivamus facilisis mollis lectus sed convallis. Pellentesque et orci elit. Nulla pellentesque viverra quam, nec viverra leo ullamcorper eget. Aliquam erat volutpat. In gravida suscipit risus, vel pulvinar nisl gravida eget. Aliquam viverra urna at urna blandit ornare. Aliquam at hendrerit massa. Maecenas id est at risus pretium cursus. Nullam porta venenatis sem, ut pellentesque lacus condimentum ut.

Donec tincidunt laoreet est, sit amet fringilla enim tincidunt ut. Phasellus erat mi, vulputate a placerat nec, egestas eu turpis. Praesent placerat, est a accumsan semper, magna nisl faucibus magna, id fermentum mi eros in arcu. Integer sodales nibh in mi bibendum, ultricies blandit mauris volutpat. Maecenas pharetra tellus eget sodales iaculis. Maecenas vel ipsum vel mi lobortis suscipit. Quisque eget ex orci. Curabitur at justo risus. Vestibulum eget erat non turpis lacinia bibendum. Fusce ante mauris, accumsan id lacus eu, fermentum fermentum risus. Integer blandit ex cursus ullamcorper lobortis. Aenean malesuada, sem ut laoreet ultrices, libero tellus pellentesque mi, nec iaculis felis lorem blandit metus. Praesent eget lacinia tellus. Duis in accumsan augue. In viverra erat sed porta congue.

Ut porta, lacus ut convallis malesuada, nunc risus feugiat lectus, vel rutrum lacus quam vel ipsum. Donec dictum nibh vitae arcu sodales iaculis. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Mauris sed laoreet dolor, dictum ultricies felis. Nunc id libero faucibus, molestie tortor in, sodales neque. Ut sit amet eros ultrices libero ultrices elementum ut gravida quam. Mauris mollis tempor orci, in vehicula justo. Curabitur facilisis sollicitudin massa, eu vehicula risus pulvinar sollicitudin. Aliquam diam nibh, facilisis vel finibus ut, consequat in lorem. Ut sagittis vel mauris ut aliquet. Cras eu ultrices mauris, et condimentum lectus. Fusce dui velit, auctor at turpis sit amet, venenatis molestie odio.

Proin auctor magna in ipsum rhoncus, non mollis urna accumsan. Aenean tincidunt nisl a massa bibendum, non malesuada orci suscipit. Proin scelerisque luctus sollicitudin. In hac habitasse platea dictumst. Vestibulum cursus, lorem ac gravida ultrices, lectus mi efficitur eros, quis iaculis magna diam at nunc. Nulla at metus id libero tincidunt interdum ut a lorem. Etiam blandit libero erat, id accumsan libero molestie ut. Praesent interdum, ex at blandit tempus, ante elit porttitor ipsum, non varius ligula mi rhoncus massa. Donec in ligula congue est dictum mattis at sit amet lacus. Suspendisse non ullamcorper tortor, sed faucibus purus. Aenean neque augue, blandit non ultricies a, tincidunt quis nibh. Aliquam faucibus non erat id tempor.',
            'cmsuser_id' => '1',
        ]);
    }
}
