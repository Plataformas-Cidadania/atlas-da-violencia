<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class NoticiaController extends Controller
{
    public function listar(){

        $lang =  App::getLocale();
        $noticias = DB::table('noticias')->where('idioma_sigla', $lang)->paginate(10);
       
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
