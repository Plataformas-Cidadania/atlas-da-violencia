<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class QuemController extends Controller
{
    public function detalhar($id=null){

        $lang =  App::getLocale();

        $quem = DB::table('quemsomos')->where('idioma_sigla', $lang)->where('id', $id)->orderBy('titulo')->first();
        if($id){
            $quem = DB::table('quemsomos')->where('idioma_sigla', $lang)->orderBy('titulo')->first();
        }

        $menus = DB::table('quemsomos')->where('idioma_sigla', $lang)->where('tipo', 1)->where('origem_id', 1)->orderBy('titulo')->get();

        return view('quem.detalhar', ['quem' => $quem, 'menus' => $menus]);

    }
}
