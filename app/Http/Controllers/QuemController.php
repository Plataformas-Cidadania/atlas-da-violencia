<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class QuemController extends Controller
{
    public function detalhar($origem_id){

        $quem = DB::table('quemsomos')->where('id', $origem_id)->first();
        $menus = DB::table('quemsomos')->where('origem_id', 1)->get();

        return view('quem.detalhar', ['quem' => $quem, 'menus' => $menus]);

    }
}
