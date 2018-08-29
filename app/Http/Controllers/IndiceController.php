<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Jenssegers\Date\Date;

class IndiceController extends Controller
{
    public function indice(){
        $lang =  App::getLocale();

        $indices = DB::table('indices')->where('idioma_sigla', $lang)->where('status', 1)->orderBy('posicao')->take(4)->get();

        return $indices;
    }
}


