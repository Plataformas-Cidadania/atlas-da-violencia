<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PgController extends Controller
{
    public function detalhar($id){

        $lang =  App::getLocale();

        $quem = DB::table('quemsomos')->where('id', $id)->where('idioma_sigla', $lang)->first();

        return view('pg.detalhar', ['quem' => $quem]);


    }
}
