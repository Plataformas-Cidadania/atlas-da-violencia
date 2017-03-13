<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\DB;

class LinkController extends Controller
{
    public function redirection($id){
        $texto = DB::table('quemsomos')->where('tipo', 3)->first();
        $link = DB::table('links')->where('id', $id)->first();

        return view('link.redirection', ['link' => $link, 'texto' => $texto]);
    }
    public function detalhar($id){

        $link = new \App\Link;
        $link = $link->find($id);

        return view('link.detalhar', ['link' => $link]);

    }
}
