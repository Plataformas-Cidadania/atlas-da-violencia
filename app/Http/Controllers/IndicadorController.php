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

        $indicador = DB::table('quemsomos')->where('tipo', 4)->first();
        $menuIndicadores = DB::table('webindicadores')->get();
        $indicadores = DB::table('webindicadores')->get();

        return view('indicador.detalhar', ['indicador' => $indicador, 'menuIndicadores' => $menuIndicadores, 'indicadores' => $indicadores]);

    }
}
