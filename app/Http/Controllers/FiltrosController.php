<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FiltrosController extends Controller
{

    private $cache;

    public function __construct(\Illuminate\Cache\Repository $cache){
        $this->indicadores = config('constants.indicadores');
        $this->abrangencias = config('constants.abrangencias');
        $this->cache = $cache;
    }

    public function index($id = 0, $tema = null){

        return view('serie.filtros-series', ['id' => $id]);

    }

    public function temas($tema_id){
        $temas = [];
        $todos = new \stdClass();

        $todos->id = 0;
        $todos->tema = "Todos";
        $todos->position = 0;
        array_push($temas, $todos);

        $temasBd = \App\Tema::where('tema_id', $tema_id)->orderBy('tema')->get();

        foreach ($temasBd as $tema) {
            array_push($temas, $tema);
        }

        return $temas;
    }

    public function indicadores($tema_id){
        foreach($this->indicadores as $key => $indicador){
            $series = \App\Serie::join('temas_series', 'temas_series.serie_id', '=', 'series.id')
                ->where('temas_series.tema_id', $tema_id)
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

        $cacheKey = 'series-'.$parameters['tema_id'].'-'.$parameters['indicador'].'-'.$parameters['abrangencia'].'-'.$idioma;

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
                    ['series.indicador', $parameters['indicador']],
                    ['valores_series.tipo_regiao', $parameters['abrangencia']]
                ])
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
