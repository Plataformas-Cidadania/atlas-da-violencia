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
        $links = DB::table('links')->take(10)->get();
        $bemvindo = DB::table('quemsomos')->where('tipo', 0)->first();
        $webdoors = DB::table('webdoors')->orderBy('id', 'desc')->take(4)->get();
        $noticias = DB::table('noticias')->orderBy('id', 'desc')->take(2)->get();
        $menu = \App\Menu::where('menu_id', 0)->get();

        return view('home', ['bemvindo' => $bemvindo, 'noticias' => $noticias, 'links' => $links, 'tags' => $tags, 'webdoors' => $webdoors, 'menu' => $menu]);
    }
}


