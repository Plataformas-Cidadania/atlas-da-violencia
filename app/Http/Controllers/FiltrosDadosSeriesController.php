<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\DB;

class FiltrosDadosSeriesController extends Controller
{
    public function __construct(\Illuminate\Cache\Repository $cache){
        $this->indicadores = config('constants.indicadores');
        $this->abrangencias = config('constants.abrangencias');
        $this->cache = $cache;
    }

    public function index($tema_id = null){
        return view('filtros-dados-series', ['tema_id' => $tema_id]);
    }

    public function temas($tema_id){
        $temas = \App\Tema::where('tema_id', $tema_id)->orderBy('tema')->get();

        return $temas;
    }

    public function indicadores($tema_id){

        foreach($this->indicadores as $key => $indicador){
            $series = \App\Serie::join('temas_series', 'temas_series.serie_id', '=', 'series.id')
                ->when($tema_id > 0, function($query) use ($tema_id){
                    return $query->where('temas_series.tema_id', $tema_id);
                })
                ->where('indicador', $indicador['id'])
                ->get();

            if(count($series) > 0){
                $this->indicadores[$key]['enable'] = true;
            }
        }
        return $this->indicadores;
    }

    public function abrangencias($tema_id){
        foreach($this->abrangencias as $key => $abrangencia){
            $cacheKey = 'qtd-series-abrangencia-'.$abrangencia['id'].'-tema-'.$tema_id;
            if(!$this->cache->has($cacheKey)){
                $this->cache->put($cacheKey, \App\Serie::join('temas_series', 'temas_series.serie_id', '=', 'series.id')
                    ->join('valores_series', 'valores_series.serie_id', '=', 'series.id')
                    ->when($tema_id > 0, function($query) use ($tema_id){
                        return $query->where('temas_series.tema_id', $tema_id);
                    })
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

        $idioma = "pt_BR";

        $where = [];

        if($idioma){
            array_push($where, ['textos_series.idioma_sigla', $idioma]);
        }
        if($parameters['tema_id']){
            array_push($where, ['temas_series.tema_id', $parameters['tema_id']]);
        }
        if($parameters['indicador']){
            array_push($where, ['series.indicador', $parameters['indicador']]);
        }
        if($parameters['abrangencia']){
            array_push($where, ['valores_series.tipo_regiao', $parameters['abrangencia']]);
        }
        if($request->search){
            array_push($where, ['textos_series.titulo', 'like', '%'.$request->search.'%']);
        }

        $series = DB::table('series')
                ->select(DB::raw('series.id, textos_series.titulo, abrangencias.titulo as abrangencia, unidades.titulo as titulo_unidade, periodicidades.titulo as periodicidade, min(valores_series.periodo) as min, max(valores_series.periodo) as max, valores_series.tipo_regiao'))
                ->join('unidades', 'series.unidade', '=', 'unidades.id')
                ->join('valores_series', 'valores_series.serie_id', '=', 'series.id')
                ->join('periodicidades', 'series.periodicidade_id', '=', 'periodicidades.id')
                ->join('temas_series', 'temas_series.serie_id', '=', 'series.id')
                ->join('textos_series', 'textos_series.serie_id', '=', 'series.id')
                ->join('abrangencias', 'valores_series.tipo_regiao', '=', 'abrangencias.id')
                ->where($where)
                ->groupBy('series.id', 'valores_series.tipo_regiao', 'periodicidades.titulo', 'textos_series.titulo', 'unidades.titulo', 'abrangencias.titulo')
                ->orderBy('textos_series.titulo')
                ->get();

        return $series;
    }

}
