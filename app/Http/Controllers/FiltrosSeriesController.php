<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FiltrosSeriesController extends Controller
{

    private $cache;

    public function __construct(\Illuminate\Cache\Repository $cache){
        $this->indicadores = config('constants.indicadores');
        $this->abrangencias = config('constants.abrangencias');
        $this->cache = $cache;
    }

    public function index($id = null, $tema = null){

        return view('serie.novo-filtros-series', ['id' => $id]);

    }

    public function temas($tema_id){
        $temas = \App\Tema::where('tema_id', $tema_id)->orderBy('tema')->get();

        return $temas;
    }

    public function indicadores(Request $request){
        $tema_id = $request->conditions['tema_id'];
        $search = $request->search;


        $indicadores = \App\Indicador::select('indicadores.id', 'indicadores.titulo as title')
            ->join('series', 'series.indicador', '=', 'indicadores.id')
            ->join('temas_series', 'temas_series.serie_id', '=', 'series.id')
            ->where('temas_series.tema_id', $tema_id)
            ->where('indicadores.titulo', 'ilike', "$search%")
            ->get();

        return $indicadores;
    }

    public function abrangencias(Request $request){

        $tema_id = $request->conditions['tema_id'];

        foreach($this->abrangencias as $key => $abrangencia){
            $cacheKey = 'qtd-series-abrangencia-'.$abrangencia['id'].'-tema-'.$tema_id;
            //exclui o cache. Utilizar apenas para testes.
            $this->cache->forget($cacheKey);
            if(!$this->cache->has($cacheKey)){
                $this->cache->put($cacheKey, \App\Serie::join('temas_series', 'temas_series.serie_id', '=', 'series.id')
                    ->join('valores_series', 'valores_series.serie_id', '=', 'series.id')
                    ->where('temas_series.tema_id', $tema_id)
                    ->where('tipo_regiao', $abrangencia['id'])
                    ->count(), 60);
            }
            $series = $this->cache->get($cacheKey);

            if($series > 0){
                $this->abrangencias[$key]['enable'] = true;
            }
        }
        return $this->abrangencias;
    }

    public function series(Request $request){
        $parameters = $request->parameters;

        //return $parameters;

        $idioma = "pt_BR";

        if(!array_key_exists('tema_id', $parameters)){
            $parameters['tema_id'] = 0;
            $temas = [];
        }

        if($parameters['tema_id'] > 0){
            $temas = \App\Tema::select('id')->where('tema_id', $parameters['tema_id'])->orWhere('id', $parameters['tema_id'])->pluck('id');
        }


        if(!array_key_exists('indicadores', $parameters)){
            $parameters['indicadores'] = [];
        }
        if(!array_key_exists('abrangencias', $parameters)){
            $parameters['abrangencias'] = [];
        }

        $str_indicadores = implode('-', $parameters['indicadores']);
        $str_abrangencias = implode('-', $parameters['abrangencias']);

        $cacheKey = 'series-'.$parameters['tema_id'].'-'.$str_indicadores.'-'.$str_abrangencias.'-'.$idioma;


        //exclui o cache. Utilizar apenas para testes.
        $this->cache->forget($cacheKey);

        //DB::connection()->enableQueryLog();

        if(!$this->cache->has($cacheKey)){
            $this->cache->put($cacheKey, DB::table('series')
                ->select('series.id', 'textos_series.titulo as titulo', 'unidades.titulo as titulo_unidade', 'periodicidades.titulo as periodicidade')
                ->join('unidades', 'series.unidade', '=', 'unidades.id')
                ->join('periodicidades', 'series.periodicidade_id', '=', 'periodicidades.id')
                ->join('temas_series', 'temas_series.serie_id', '=', 'series.id')
                ->join('textos_series', 'textos_series.serie_id', '=', 'series.id')
                ->where([
                    ['textos_series.idioma_sigla', $idioma],
                    ['textos_series.titulo', 'ilike', '%'.$parameters['search'].'%']
                ])
                ->when(!empty($parameters['indicadores']), function($query) use ($parameters){
                    return $query->whereIn('series.indicador', $parameters['indicadores']);
                })
                ->when(!empty($temas), function($query) use ($temas){
                    return $query->whereIn('tema_id', $temas);
                })
                ->orderBy('textos_series.titulo')
                ->paginate($parameters['limit']),
                60);
        }

        $series = $this->cache->get($cacheKey);

        //return DB::getQueryLog();

        return $series;
    }

    public function territoriosSerieAbrangencia(Request $request){
        $tabelas = [
            1 => 'spat.ed_territorios_paises',
            2 => 'spat.ed_territorios_regioes',
            3 => 'spat.ed_territorios_uf',
            4 => 'spat.ed_territorios_municipios',
            5 => 'spat.ed_territorios_microrregioes',
            6 => 'spat.ed_territorios_mesoregioes',
            7 => 'spat.ed_territorios_piaui_tds'
        ];

        $abrangencia = $request->abrangencia;
        $id = $request->id;

        $abrangencias = DB::table($tabelas[$abrangencia])
            ->select('edterritorios_codigo')
            ->join('valores_series', 'valores_series.regiao_id', '=', $tabelas[$abrangencia].'.edterritorios_codigo')
            ->where('valores_series.serie_id', $id)
            ->where('valores_series.tipo_regiao', $abrangencia)
            ->distinct()
            ->pluck('edterritorios_codigo');

        //Log::info(DB::getQueryLog());

        return $abrangencias;
    }
}
