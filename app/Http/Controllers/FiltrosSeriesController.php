<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\App;
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

    public function index($id = 0, $tema = null){

        /*if($id==null){
            $tema = \App\Tema::where('tema_id', 0)->first();
            $id = $tema->id;
        }*/

        $setting = DB::table('settings')->orderBy('id', 'desc')->first();
        $consulta_por_temas = $setting->consulta_por_temas;

        return view('serie.novo-filtros-series', ['id' => $id, 'consulta_por_temas' => $consulta_por_temas]);

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
            ->when($tema_id>0, function($query) use ($tema_id){
                return $query->where('temas_series.tema_id', $tema_id);
            })
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

        $setting = DB::table('settings')->orderBy('id', 'desc')->first();
        $consulta_por_temas = $setting->consulta_por_temas;

        $parameters = $request->parameters;

        //return $parameters;
        $lang =  App::getLocale();

        //$idioma = "pt_BR";

        $temas = [];

        if(!array_key_exists('tema_id', $parameters)){
            $parameters['tema_id'] = 0;
            $temas = [];
        }

        if($parameters['tema_id'] > 0 && $consulta_por_temas == 0){
            $temas = \App\Tema::select('id')->where('tema_id', $parameters['tema_id'])->orWhere('id', $parameters['tema_id'])->pluck('id');
        }

        //Log::info([$temas]);

        if(!array_key_exists('indicadores', $parameters)){
            $parameters['indicadores'] = [];
        }
        if(!array_key_exists('abrangencias', $parameters)){
            $parameters['abrangencias'] = [];
        }

        $str_indicadores = implode('-', $parameters['indicadores']);
        $str_abrangencias = implode('-', $parameters['abrangencias']);

        $cacheKey = 'series-'.$parameters['tema_id'].'-'.$str_indicadores.'-'.$str_abrangencias.'-'.$lang;


        /*//exclui o cache. Utilizar apenas para testes.
        $this->cache->forget($cacheKey);

        //DB::connection()->enableQueryLog();



        if(!$this->cache->has($cacheKey)){
            $this->cache->put($cacheKey, DB::table('series')
                ->select('series.id', 'series.tipo_dados', 'textos_series.titulo as titulo', 'idiomas_unidades.titulo as titulo_unidade', 'idiomas_periodicidades.titulo as periodicidade')
                ->join('unidades', 'unidades.id', '=', 'series.unidade')
                ->join('idiomas_unidades', 'idiomas_unidades.unidade_id', '=', 'unidades.id')
                ->join('periodicidades', 'periodicidades.id', '=', 'series.periodicidade_id')
                ->join('idiomas_periodicidades', 'idiomas_periodicidades.periodicidade_id', '=', 'periodicidades.id')
                ->join('temas_series', 'temas_series.serie_id', '=', 'series.id')
                ->join('textos_series', 'textos_series.serie_id', '=', 'series.id')
                ->where([
                    ['textos_series.idioma_sigla', $lang],
                    ['textos_series.titulo', 'ilike', '%'.$parameters['search'].'%'],
                    ['status', 1]
                ])
                ->where('idiomas_periodicidades.idioma_sigla', $lang)
                ->where('idiomas_unidades.idioma_sigla', $lang)
                ->when(!empty($parameters['indicadores']), function($query) use ($parameters){
                    return $query->whereIn('series.indicador', $parameters['indicadores']);
                })
                ->when(!empty($temas), function($query) use ($temas){
                    return $query->whereIn('tema_id', $temas);
                })
                ->when($consulta_por_temas == 1, function($query) use ($parameters){
                    return $query->where('tema_id', $parameters['tema_id']);
                })
                ->orderBy('textos_series.titulo')
		        ->distinct()
                ->paginate($parameters['limit']),
                60);
        }


        $series = $this->cache->get($cacheKey);*/

        $search = $parameters['search'];

        $series = DB::table('series')
            ->select('series.id', 'series.tipo_dados', 'textos_series.titulo as titulo', 'idiomas_unidades.titulo as titulo_unidade', 'idiomas_periodicidades.titulo as periodicidade')
            ->join('unidades', 'unidades.id', '=', 'series.unidade')
            ->join('idiomas_unidades', 'idiomas_unidades.unidade_id', '=', 'unidades.id')
            ->join('periodicidades', 'periodicidades.id', '=', 'series.periodicidade_id')
            ->join('idiomas_periodicidades', 'idiomas_periodicidades.periodicidade_id', '=', 'periodicidades.id')
            ->join('temas_series', 'temas_series.serie_id', '=', 'series.id')
            ->join('textos_series', 'textos_series.serie_id', '=', 'series.id')
            ->where([
                ['textos_series.idioma_sigla', $lang],
                /*['textos_series.titulo', 'ilike', '%'.$parameters['search'].'%'],*/
                ['status', 1]
            ])
            ->whereRaw("unaccent(textos_series.titulo) ilike  unaccent('%$search%')")
            ->where('idiomas_periodicidades.idioma_sigla', $lang)
            ->where('idiomas_unidades.idioma_sigla', $lang)
            ->when(!empty($parameters['indicadores']), function($query) use ($parameters){
                return $query->whereIn('series.indicador', $parameters['indicadores']);
            })
            ->when(!empty($temas), function($query) use ($temas){
                return $query->whereIn('tema_id', $temas);
            })
            ->when($consulta_por_temas == 1, function($query) use ($parameters){
                return $query->where('tema_id', $parameters['tema_id']);
            })
            ->orderBy('textos_series.titulo')
            ->groupBy('series.id', 'series.tipo_dados', 'textos_series.titulo', 'idiomas_unidades.titulo', 'idiomas_periodicidades.titulo')
            ->paginate($parameters['limit']);

        //Log::info(count($series));

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

    public function getOpcoesDownloadSerie($id){

        $lang =  App::getLocale();

        $abrangencias = \App\ValorSerie::select('valores_series.tipo_regiao', 'idiomas_options_abrangencias.title')
            ->join('options_abrangencias', 'options_abrangencias.id', '=', 'valores_series.tipo_regiao')
            ->join('idiomas_options_abrangencias', 'idiomas_options_abrangencias.option_abrangencia_id', '=', 'options_abrangencias.id')
            ->where('valores_series.serie_id', $id)
            ->distinct()
            ->get();

        $downloadsExtras = \App\Download::where('origem', 1)->where('origem_id', $id)->where('idioma_sigla', $lang)->get();

        $serie = \App\Serie::select('textos_series.titulo')
            ->join('textos_series', 'textos_series.serie_id', '=', 'series.id')
            ->where('idioma_sigla', $lang)
            ->where('series.id', $id)
            ->first()->titulo;

        return ['abrangencias' => $abrangencias, 'downloadsExtras' => $downloadsExtras, 'serie' => $serie];

    }
}
