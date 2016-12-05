<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\DB;

class NoticiaController extends Controller
{
    public function listar(){
        $noticias = DB::table('noticias')->paginate(10);
       
        return view('noticia.listar', ['noticias' => $noticias]);
    }
    public function detalhar($id){

        //$id = intval($id);
        $noticia = new \App\Noticia;
        $noticia = $noticia->find($id);

        /*$noticia = DB::table('noticias')
            ->where([
                ['id', '=', $id],
                ['titulo', '=', $titulo]
            ])->get();*/


        return view('noticia.detalhar', ['noticia' => $noticia]);
        
        //return view('noticia.detalhar');
    }
}
