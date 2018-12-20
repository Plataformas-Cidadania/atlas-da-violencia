<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class IndicadorController extends Controller
{
    public function detalhar(){
        $lang =  App::getLocale();

        $indicador = DB::table('quemsomos')->where('idioma_sigla', $lang)->where('tipo', 5)->first();
        $menuIndicadores = DB::table('webindicadores')->where('idioma_sigla', $lang)->orderBy('posicao')->get();
        $indicadores = DB::table('webindicadores')->where('idioma_sigla', $lang)->get();

        return view('indicador.detalhar', ['indicador' => $indicador, 'menuIndicadores' => $menuIndicadores, 'indicadores' => $indicadores]);

    }
}
