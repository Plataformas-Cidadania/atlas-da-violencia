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

        DB::connection()->enableQueryLog();

        if(!$this->cache->has($cacheKey)){
            $this->cache->put($cacheKey, DB::table('series')
                ->select(DB::raw('series.id, textos_series.titulo, unidades.titulo as titulo_unidade, periodicidades.titulo as periodicidade, min(valores_series.periodo) as min, max(valores_series.periodo) as max, valores_series.tipo_regiao'))
                ->join('unidades', 'series.unidade', '=', 'unidades.id')
                ->join('valores_series', 'valores_series.serie_id', '=', 'series.id')
                ->join('periodicidades', 'series.periodicidade_id', '=', 'periodicidades.id')
                ->join('temas_series', 'temas_series.serie_id', '=', 'series.id')
                ->join('textos_series', 'textos_series.serie_id', '=', 'series.id')
                ->where([
                    ['textos_series.idioma_sigla', $idioma],
                    ['temas_series.tema_id', $parameters['tema_id']],
                    ['textos_series.titulo', $parameters['search']]
                    /*['series.indicador', $parameters['indicador']],*/
                    /*['valores_series.tipo_regiao', $parameters['abrangencia']]*/
                ])
                ->when(!empty($parameters['indicador']), function($query) use ($parameters){
                    return $query->whereIn('series.indicador', $parameters['indicadores']);
                })
                ->when(!empty($parameters['abrangencia']), function($query) use ($parameters){
                    return $query->whereIn('series.tipo_regiao', $parameters['abrangencias']);
                })
                //->orWhere('series.serie_id', $parameters['id'])
                ->groupBy('series.id', 'valores_series.tipo_regiao', 'periodicidades.titulo', 'textos_series.titulo', 'unidades.titulo')
                ->orderBy('textos_series.titulo')
                ->get(), 60);
        }

        $series = $this->cache->get($cacheKey);

        //return DB::getQueryLog();

        return $series;
    }
}
