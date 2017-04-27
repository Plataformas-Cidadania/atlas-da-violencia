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
        $menus = DB::table('quemsomos')->where('idioma_sigla', $lang)->where('tipo', 1)->where('origem_id', 1)->orderBy('posicao')->get();

        if($id){

            $quem = DB::table('quemsomos')->where('idioma_sigla', $lang)->where('id', $id)->orderBy('titulo')->first();
            if(count($quem)==0){
                return redirect("quem/");
            }

            return view('quem.detalhar', ['quem' => $quem, 'menus' => $menus, 'id' => $id]);


        }

        $quem = DB::table('quemsomos')->where('idioma_sigla', $lang)->where('id', $menus[0]->id)->orderBy('titulo')->first();




        return view('quem.detalhar', ['quem' => $quem, 'menus' => $menus, 'id' => $id]);

    }
}
