<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use Jenssegers\Date\Date;

class HomeController extends Controller
{
    public function index(){
        $tags = DB::table('links')->select('tags')->groupBy('tags')->orderBy('tags')->get();
        $links = DB::table('links')->orderBy('posicao')->take(10)->get();
        $bemvindo = DB::table('quemsomos')->where('tipo', 0)->first();
        $webdoors = DB::table('webdoors')->orderBy('id', 'desc')->take(4)->get();
        $ultimaNoticia = DB::table('noticias')->orderBy('id', 'desc')->take(1)->first();
        $noticias = DB::table('noticias')->orderBy('id', 'desc')->skip(1)->take(2)->get();
        $menu = \App\Menu::where('menu_id', 0)->get();
        $video = \App\Video::orderBy('id', 'desc')->first();
        $indices = \App\Indice::orderBy('posicao')->where('status', 1)->take(4)->get();


        return view('home', [
            'bemvindo' => $bemvindo,
            'ultimaNoticia' => $ultimaNoticia,
            'noticias' => $noticias,
            'links' => $links,
            'tags' => $tags,
            'webdoors' => $webdoors,
            'menu' => $menu,
            'video' => $video,
            'indices' => $indices
        ]);
    }
}


