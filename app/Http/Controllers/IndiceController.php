<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use Jenssegers\Date\Date;

class IndiceController extends Controller
{
    public function indice(){

        $indices = DB::table('indices')->orderBy('posicao')->where('status', 1)->get();

        return $indices;
    }
}


