<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use App\Http\Requests;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ApiController extends Controller
{

    private $lang = null;

    public function __construct(){
        $this->lang =  App::getLocale();
    }

    public function index(){


        $textoApi = DB::table('quemsomos')->where('idioma_sigla', $this->lang)->where('tipo', 10)->orderBy('posicao')->first();
        $apis = DB::table('apis')
            ->select('idiomas_apis.titulo', 'idiomas_apis.descricao', 'apis.tipo', 'apis.url', 'apis.resposta')
            ->join('idiomas_apis', 'idiomas_apis.api_id', '=', 'apis.id')
            ->where('idiomas_apis.idioma_sigla', $this->lang)
            ->orderBy('idiomas_apis.id')
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
        $fonte = \App\Fonte::select('id', 'titulo')
            ->where('id', $id)
            ->first();

        return $fonte;
    }
    ////////////////////////////////////////////////
    /////////////////UNIDADES///////////////////////
    public function unidades(){
        $unidades = \App\Unidade::select('unidades.id', 'idiomas_unidades.titulo')
            ->join('idiomas_unidades', 'idiomas_unidades.unidade_id', '=', 'unidades.id')
            ->where('idiomas_unidades.idioma_sigla', $this->lang)
            ->orderBy('titulo')
            ->get();

        return $unidades;
    }
    public function unidade($id){
        $unidade = \App\Unidade::select('unidades.id', 'idiomas_unidades.titulo')
            ->join('idiomas_unidades', 'idiomas_unidades.unidade_id', '=', 'unidades.id')
            ->where('idiomas_unidades.idioma_sigla', $this->lang)
            ->where('unidades.id', $id)
            ->first();

        return $unidade;
    }
    ////////////////////////////////////////////////
    //////////////PERIODICIDADES////////////////////
    public function periodicidades(){
        $periodicidades = \App\Periodicidade::select('periodicidades.id', 'idiomas_periodicidades.titulo')
            ->join('idiomas_periodicidades', 'idiomas_periodicidades.periodicidade_id', '=', 'periodicidades.id')
            ->where('idiomas_periodicidades.idioma_sigla', $this->lang)
            ->orderBy('titulo')
            ->get();

        return $periodicidades;
    }
    public function periodicidade($id){
        $periodicidade = \App\Periodicidade::select('periodicidades.id', 'idiomas_periodicidades.titulo')
            ->join('idiomas_periodicidades', 'idiomas_periodicidades.periodicidade_id', '=', 'periodicidades.id')
            ->where('idiomas_periodicidades.idioma_sigla', $this->lang)
            ->where('periodicidades.id', $id)
            ->first();

        return $periodicidade;
    }
    ////////////////////////////////////////////////
    /////////////////INDICADORES////////////////////
    public function indicadores(){
        $indicadores = \App\Indicador::select('indicadores.id', 'idiomas_indicadores.titulo')
            ->join('idiomas_indicadores', 'idiomas_indicadores.periodicidade_id', '=', 'indicadores.id')
            ->where('idiomas_indicadores.idioma_sigla', $this->lang)
            ->orderBy('titulo')
            ->get();

        return $indicadores;
    }
    public function indicador($id){
        $indicador = \App\Indicador::select('indicadores.id', 'idiomas_indicadores.titulo')
            ->join('idiomas_indicadores', 'idiomas_indicadores.periodicidade_id', '=', 'indicadores.id')
            ->where('idiomas_indicadores.idioma_sigla', $this->lang)
            ->where('indicadores.id', $id)
            ->first();

        return $indicador;
    }
    ////////////////////////////////////////////////


    public function temas(){


        $temas = \App\Tema::select('temas.id', 'idiomas_temas.titulo', 'temas.tema_id', 'temas.imagem', 'temas.tipo')
            ->join('idiomas_temas', 'idiomas_temas.tema_id', '=', 'temas.id')
            ->where('idiomas_temas.idioma_sigla', $this->lang)
            ->where('temas.status', 1)
            ->where('temas.tema_id', 0)
            ->orderBy('idiomas_temas.titulo')
            ->get();

        foreach ($temas as $tema){

            $subTemas = $this->subtemas($tema->id);

            $tema->subTemas = $subTemas;
        }


        return $temas;

    }

    public function tema($id){
        $tema = \App\Tema::select('temas.id', 'idiomas_temas.titulo', 'temas.imagem', 'temas.tipo')
            ->join('idiomas_temas', 'idiomas_temas.tema_id', '=', 'temas.id')
            ->where('idiomas_temas.idioma_sigla', $this->lang)
            ->where('temas.status', 1)
            ->where('temas.id', $id)
            ->first();

        return $tema;
    }

    private function subtemas($tema_id){

        $subTemas = \App\Tema::select('temas.id', 'idiomas_temas.titulo', 'temas.tema_id', 'temas.imagem', 'temas.tipo')
            ->join('idiomas_temas', 'idiomas_temas.tema_id', '=', 'temas.id')
            ->where('idiomas_temas.idioma_sigla', $this->lang)
            ->where('temas.status', 1)
            ->where('temas.tema_id', $tema_id)
            ->orderBy('idiomas_temas.titulo')
            ->get();


        foreach ($subTemas as $subTema){


            $subTemasB = $this->subtemas($subTema->id);

            $subTema->subTemas = $subTemasB;
        }

        return $subTemas;

    }
    //////////////SERIES////////////////////
    public function series($tema_id = 0){

        //DB::enableQueryLog();

        $series = \App\Serie::select('series.id', 'textos_series.titulo')
            ->join('textos_series', 'textos_series.serie_id', '=', 'series.id')
            ->where('textos_series.idioma_sigla', $this->lang)
            ->when($tema_id > 0, function($query) use ($tema_id){
                $query->join('temas_series', 'temas_series.serie_id', '=', 'series.id');
                $query->where('temas_series.tema_id', $tema_id);
                return $query;
            })
            ->orderBy('textos_series.titulo')
            ->get();

        //Log::info(DB::getQueryLog());

        return $series;
    }
    public function serie($id){
        $serie = \App\Serie::select('series.id', 'textos_series.titulo')
            ->join('textos_series', 'textos_series.serie_id', '=', 'series.id')
            ->where('textos_series.idioma_sigla', $this->lang)
            ->where('series.id', $id)
            ->first();

        return $serie;
    }
    ////////////////////////////////////////////////
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
            7 => 'spat.ed_territorios_piaui_tds',
            8 => 'spat.ed_territorios_municipios',
        ];

        $regions = explode(',', $regions);

        $select_sigla = "$tabelas[$abrangencia].edterritorios_sigla";
        if($abrangencia == 4){
            $select_sigla = "$tabelas[$abrangencia].edterritorios_nome";
        }

        $periodicidade = null;
        if(!empty($inicial)) {
            $periodicidade = \App\Serie::select('periodicidades.titulo')
                ->join('periodicidades', 'periodicidades.id', '=', 'series.periodicidade_id')
                ->find($serie_id)
                ->titulo;
        }

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

        //DB::enableQueryLog();

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

        //Log::info(DB::getQueryLog());

        return $rows;
    }

}
