<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use App\Http\Requests;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ApiController extends Controller
{

    public function index(){

        $lang =  App::getLocale();
        $textoApi = DB::table('quemsomos')->where('idioma_sigla', $lang)->where('tipo', 10)->orderBy('posicao')->first();
        $apis = DB::table('apis')
            ->select('idiomas_apis.titulo', 'idiomas_apis.descricao', 'apis.tipo', 'apis.url', 'apis.resposta')
            ->join('idiomas_apis', 'idiomas_apis.api_id', '=', 'apis.id')
            ->where('idiomas_apis.idioma_sigla', $lang)
            ->orderBy('versao')
            ->get();

        return view('api.listar', ['apis' => $apis, 'textoApi' => $textoApi]);
    }
    /////////////////////FONTES/////////////////////
    public function fontes($order='titulo'){
        $fontes = \App\Fonte::select('id', 'titulo')
            ->orderBy($order)
            ->get();

        return $fontes;
    }
    public function fonte($id){
        $fontes = \App\Fonte::select('id', 'titulo')
            ->where('id', $id)
            ->first();

        return $fontes;
    }
    ////////////////////////////////////////////////
    /////////////////UNIDADES///////////////////////
    public function unidades(){
        $unidades = \App\Unidade::select('id', 'titulo')
            ->orderBy('titulo')
            ->get();

        return $unidades;
    }
    public function unidade($id){
        $unidades = \App\Unidade::select('id', 'titulo')
            ->where('id', $id)
            ->first();

        return $unidades;
    }
    ////////////////////////////////////////////////
    //////////////PERIODICIDADES////////////////////
    public function periodicidades(){
        $periodicidades = \App\Periodicidade::select('id', 'titulo')
            ->orderBy('titulo')
            ->get();

        return $periodicidades;
    }
    public function periodicidade($id){
        $periodicidades = \App\Periodicidade::select('id', 'titulo')
            ->where('id', $id)
            ->first();

        return $periodicidades;
    }
    ////////////////////////////////////////////////
    /////////////////INDICADORES////////////////////
    public function indicadores(){
        $indicadores = \App\Indicador::select('id', 'titulo')
            ->orderBy('titulo')
            ->get();

        return $indicadores;
    }
    public function indicadore($id){
        $indicadores = \App\Indicador::select('id', 'titulo')
            ->where('id', $id)
            ->first();

        return $indicadores;
    }
    ////////////////////////////////////////////////


    public function temas(){


        $temas = \App\Tema::select('id', 'tema', 'tema_id', 'imagem', 'tipo')->where('status', 1)->where('tema_id', 0)->orderBy('tema')->get();

        foreach ($temas as $tema){
            $subTemas = \App\Tema::select('id', 'tema', 'tema_id', 'imagem', 'tipo')->where('status', 1)->where('tema_id', $tema->id)->get();
        }

        $return = [
            'temas' => $temas,
            'subTemas' => $subTemas,
        ];

        return [$return];

        //return $temas;
    }

}