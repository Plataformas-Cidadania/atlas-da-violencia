<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Jenssegers\Date\Date;

class HomeController extends Controller
{
    public function index(){
        $lang =  App::getLocale();

        $tags = DB::table('links')->select('tags')->groupBy('tags')->orderBy('tags')->get();
        $links = DB::table('links')->where('idioma_sigla', $lang)->orderBy('posicao')->take(10)->get();
        $bemvindo = DB::table('quemsomos')->where('idioma_sigla', $lang)->where('tipo', 0)->first();
        $webdoors = DB::table('webdoors')->where('idioma_sigla', $lang)->orderBy('id', 'desc')->take(4)->get();
        $ultimaArtigo = DB::table('artigos')->where('idioma_sigla', $lang)->orderBy('id', 'desc')->take(1)->first();
        $noticias = DB::table('noticias')->where('idioma_sigla', $lang)->orderBy('id', 'desc')->skip(1)->take(3)->get();
        $menu = \App\Menu::where('menu_id', 0)->get();
        $video = \App\Video::orderBy('id', 'desc')->first();
        $indices = \App\Indice::where('idioma_sigla', $lang)->where('status', 1)->orderBy('posicao')->take(4)->get();
        $downloads = DB::table('downloads')->where('idioma_sigla', $lang)->where('origem_id', 0)->orderBy('id', 'desc')->take(3)->get();
        $tituloLinhaTempo = DB::table('quemsomos')->where('idioma_sigla', $lang)->where('tipo', 4)->orderBy('id', 'desc')->take(1)->get();
        $parceiros = DB::table('parceiros')->orderBy('posicao')->where('status', 1)->take(4)->get();


        return view('home', [
            'bemvindo' => $bemvindo,
            'ultimaArtigo' => $ultimaArtigo,
            'noticias' => $noticias,
            'links' => $links,
            'tags' => $tags,
            'webdoors' => $webdoors,
            'menu' => $menu,
            'video' => $video,
            'indices' => $indices,
            'downloads' => $downloads,
            'tituloLinhaTempo' => $tituloLinhaTempo,
            'parceiros' => $parceiros,
        ]);
    }

    public function newsletter(){

    }
}


