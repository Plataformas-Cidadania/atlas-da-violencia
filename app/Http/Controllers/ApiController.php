<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ApiController extends Controller
{

    public function index(){

        $lang =  App::getLocale();
        $apis = DB::table('quemsomos')->where('idioma_sigla', $lang)->where('tipo', 10)->orderBy('posicao')->get();

        return view('api.listar', ['apis' => $apis]);
    }

    public function fontes($order='titulo'){
        $fontes = \App\Fonte::select('id', 'titulo')
            ->orderBy($order)
            ->get();

        return $fontes;
    }
    public function fonte($id){
        $fontes = \App\Fonte::select('id as cd_webdoor', 'titulo as tx_titulo_webdoor', 'descricao as tx_descricao_webdoor', 'imagem as tx_imagem_webdoor', 'link as tx_link_webdoor', 'legenda as tx_legenda_webdoor')
            ->where('status', 1)
            ->where('id', $id)
            ->orderBy('posicao')
            ->get();

        return $fontes;
    }



    public function temas($order='tema'){
        /*$fontes = \App\Tema::select('id', 'tema', 'tema_id', 'imagem', 'tipo')
            ->orderBy($order)
            ->get();

        return $fontes;*/

        $temas = \App\Tema::select('id', 'tema', 'tema_id', 'imagem', 'tipo')->where('status', 1)->orderBy($order)->get();


        foreach ($temas as $tema){
            $subTema = \App\Tema::select('id', 'tema', 'tema_id', 'imagem', 'tipo')->where('status', 1)->where('tema_id', $tema->id)->get();
        }


        return $temas;
    }

}