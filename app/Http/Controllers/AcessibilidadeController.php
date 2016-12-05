<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AcessibilidadeController extends Controller
{
    public function detalhar(){

        $quem = DB::table('quemsomos')->where('tipo', 2)->first();
        //$settings = DB::table('settings')->orderBy('id', 'desc')->get();

        return view('acessibilidade.detalhar', ['quem' => $quem]);
        //return view('quem.detalhar', ['quem' => $quem, 'settings' => $settings]);

    }
}
