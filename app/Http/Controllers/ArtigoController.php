<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\DB;

class ArtigoController extends Controller
{
    public function listar($origem_id){
        if($origem_id==0){
            $artigos = DB::table('artigos')->paginate(10);
        }else{
            $artigos = DB::table('artigos')->where('origem_id', '=', $origem_id )->paginate(10);
        }

        $menus = DB::table('links')->get();

        return view('artigo.listar', ['artigos' => $artigos, 'menus' => $menus]);
    }
    public function detalhar($id){

        $artigo = new \App\Artigo;
        $artigo = $artigo->find($id);

        return view('artigo.detalhar', ['artigo' => $artigo]);
        
    }
}
