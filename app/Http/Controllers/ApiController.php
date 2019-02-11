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

            $subTemas = $this->subtemas($tema->id);

            $tema->subTemas = $subTemas;
        }


        return $temas;

    }

    private function subtemas($tema_id){

        $subTemas = \App\Tema::select('id', 'tema', 'tema_id', 'imagem', 'tipo')->where('status', 1)->where('tema_id', $tema_id)->get();


        foreach ($subTemas as $subTema){


            $subTemasB = $this->subtemas($subTema->id);

            $subTema->subTemas = $subTemasB;
        }

        return $subTemas;

    }

    public function todosValores($serie_id, $abrangencia, $inicial = null, $final = null){
        return $this->valores($serie_id, $abrangencia, 0, $inicial, $final);
    }

    public function valoresPorRegiao($serie_id, $abrangencia, $regions, $inicial = null, $final = null){
        return $this->valores($serie_id, $abrangencia, $regions, $inicial, $final);
    }

    private function valores($serie_id, $abrangencia, $regions, $inicial, $final){

        $tabelas = [
            1 => 'spat.ed_territorios_paises',
            2 => 'spat.ed_territorios_regioes',
            3 => 'spat.ed_territorios_uf',
            4 => 'spat.ed_territorios_municipios',
            5 => 'spat.ed_territorios_microrregioes',
            6 => 'spat.ed_territorios_mesoregioes',
            7 => 'spat.ed_territorios_piaui_tds'
        ];

        $regions = explode(',', $regions);

        $select_sigla = "$tabelas[$abrangencia].edterritorios_sigla";
        if($abrangencia == 4){
            $select_sigla = "$tabelas[$abrangencia].edterritorios_nome";
        }

        $periodicidade = \App\Serie::select('periodicidades.titulo')
            ->join('periodicidades', 'periodicidades.id', '=', 'series.periodicidade_id')
            ->find($serie_id)
            ->titulo;

        if($periodicidade=="Anual"){
            $inicial = $inicial.'-01-15';
            $final = $final.'-01-15';
        }

        if($periodicidade=="Mensal" || $periodicidade=="Trimestral" || $periodicidade=="Semestral"){
            $inicial = $inicial.'-15';
            $final = $final.'-15';
        }

        $where = [['valores_series.serie_id', $serie_id]];
        if(!empty($inicial)){
            array_push($where, ['valores_series.periodo', '>=', $inicial]);
            array_push($where, ['valores_series.periodo', '<=', $final]);
        }

        DB::enableQueryLog();

        $rows = DB::table('valores_series')
            ->select(DB::raw("$tabelas[$abrangencia].edterritorios_codigo as cod, $select_sigla as sigla, valores_series.valor, valores_series.periodo"))
            ->join($tabelas[$abrangencia], 'valores_series.regiao_id', '=', "$tabelas[$abrangencia].edterritorios_codigo")
            ->where($where)
            ->where("valores_series.tipo_regiao", $abrangencia)
            ->when($regions[0]!=0, function($query) use ($regions, $tabelas, $abrangencia){
                return $query->whereIn("$tabelas[$abrangencia].edterritorios_codigo", $regions);
            })
            //->whereIn("$tabelas[$abrangencia].edterritorios_codigo", $regions)
            ->orderBy('valores_series.periodo')
            ->get();

        Log::info(DB::getQueryLog());

        return $rows;
    }

}