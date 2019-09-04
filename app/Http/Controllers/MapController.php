<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

class MapController extends Controller
{
    private $cache;

    public function __construct(\Illuminate\Cache\Repository $cache){
        $this->cache = $cache;
    }

    function periodos($id, $abrangencia){
        $periodos = DB::table('valores_series')
            ->select('periodo')
            ->distinct('periodo')
            ->where('serie_id', $id)
            ->where('tipo_regiao', $abrangencia)
            ->orderBy('periodo')->get();

        /*$serie = \App\Serie::select('periodicidade.titulo')
            ->join('periodicidades', 'periodicidades.id', 'series.periodicidade_id')
            ->first();*/

        $retorno = [];
        foreach($periodos as $index => $periodo){
            $retorno[$index] = $periodo->periodo;
        }

        return $retorno;
    }

    function valoresRegiaoPorPeriodoGeometry($id, $tipoValores, $min, $max){

        //1 - Numérico Incremental / 2 - Numérico Agregado / 3 - Taxa

        //ST_X(edterritorios_centroide), ST_Y(edterritorios_centroide)

        $where = [
            ['valores_series.serie_id', $id],
            ['valores_series.periodo', '>=', $min],
            ['valores_series.periodo', '<=', $max]
        ];
        if($tipoValores==2){
            $where = [
                ['valores_series.serie_id', $id],
                ['valores_series.periodo', $max]
            ];
        }

        $valores = DB::table('valores_series')
            ->select(
                DB::raw(
                    "
                    ST_AsGeoJSON(ed_territorios_uf.edterritorios_geometry) as geometry, 
                    valores_series.valor as total, 
                    valores_series.uf, 
                    ed_territorios_uf.edterritorios_nome as nome, 
                    ST_X(ed_territorios_uf.edterritorios_centroide) as x, 
                    ST_Y(ed_territorios_uf.edterritorios_centroide) as y
                    "
                ))
            ->join('ed_territorios_uf', 'valores_series.uf', '=', 'ed_territorios_uf.edterritorios_sigla')
            ->where($where)
            //->groupBy('valores_series.uf', 'ed_territorios_uf.edterritorios_geometry', 'ed_territorios_uf.edterritorios_centroide', 'ed_territorios_uf.edterritorios_nome')
            ->orderBy('total')
            ->get();

        $areas = [];
        $areas['type'] = 'FeatureCollection';
        $areas['features'] = [];
        foreach($valores as $index => $valor){
            $areas['features'][$index]['type'] = 'Feature';
            $areas['features'][$index]['id'] = $index;
            $areas['features'][$index]['properties']['uf'] = $valor->uf;
            $areas['features'][$index]['properties']['nome'] = $valor->nome;
            $areas['features'][$index]['properties']['total'] = $valor->total;
            $areas['features'][$index]['properties']['x'] = $valor->x;
            $areas['features'][$index]['properties']['y'] = $valor->y;
            $areas['features'][$index]['geometry'] = json_decode($valor->geometry);
        }


        return $areas;
    }

    function valoresRegiaoPeriodoGeometry($id, $periodo, $regions, $abrangencia){

        //1 - Numérico Incremental / 2 - Numérico Agregado / 3 - Taxa

        //ST_X(edterritorios_centroide), ST_Y(edterritorios_centroide)

        //$periodo = substr($periodo, 0, 4);

        $cacheKeyValores = sha1('regiao-variavel-valores'.$id.'-'.$periodo.'-'.str_replace(',', '', $regions).'-'.$abrangencia);
        $cacheKeyArea = sha1('regiao-variavel-area'.$id.'-'.$periodo.'-'.str_replace(',', '', $regions).'-'.$abrangencia);

        $regions = explode(',', $regions);

        $tabelas = [
            1 => 'spat.ed_territorios_paises',
            2 => 'spat.ed_territorios_regioes',
            3 => 'spat.ed_territorios_uf',
            4 => 'spat.ed_territorios_municipios',
            5 => 'spat.ed_territorios_microrregioes',
            6 => 'spat.ed_territorios_mesoregioes',
            7 => 'spat.ed_territorios_piaui_tds'
        ];

        $where = [
            ['valores_series.serie_id', $id],
            ['valores_series.periodo', $periodo],
            ['valores_series.tipo_regiao', $abrangencia],
        ];

        $select_sigla = "$tabelas[$abrangencia].edterritorios_sigla";
        if($abrangencia == 4){
            $select_sigla = "$tabelas[$abrangencia].edterritorios_nome";
        }

        //DB::connection()->enableQueryLog();

        //exclui o cache. Utilizar apenas para testes.
        $this->cache->forget($cacheKeyValores);

        if(!$this->cache->has($cacheKeyValores)){
            $this->cache->put($cacheKeyValores, DB::table('valores_series')
                ->select(
                    DB::raw(
                        "
                    ST_AsGeoJSON($tabelas[$abrangencia].edterritorios_geometry) as geometry, 
                    valores_series.valor as total,
                    $select_sigla as sigla,
                    $tabelas[$abrangencia].edterritorios_nome as nome, 
                    ST_X($tabelas[$abrangencia].edterritorios_centroide) as x, 
                    ST_Y($tabelas[$abrangencia].edterritorios_centroide) as y
                    "
                    ))
                ->join("$tabelas[$abrangencia]", 'valores_series.regiao_id', '=', "$tabelas[$abrangencia].edterritorios_codigo")
                ->where($where)
                /*->whereYear("$tabelas[$abrangencia].edterritorios_data_inicial", '<=', $periodo)
                ->whereYear("$tabelas[$abrangencia].edterritorios_data_final", '>=', $periodo)*/
                ->where("$tabelas[$abrangencia].edterritorios_data_inicial", '<=', $periodo)
                ->where("$tabelas[$abrangencia].edterritorios_data_final", '>=', $periodo)

                ->when($regions[0]!=0, function($query) use ($regions, $tabelas, $abrangencia){
                    return $query->whereIn("$tabelas[$abrangencia].edterritorios_codigo", $regions);
                })
                /*->whereIn("$tabelas[$abrangencia].edterritorios_codigo", $regions)*/
                //->groupBy("$tabelas[$abrangencia].edterritorios_sigla", "$tabelas[$abrangencia].edterritorios_nome", "$tabelas[$abrangencia].edterritorios_geometry", "$tabelas[$abrangencia].edterritorios_centroide")
                ->orderBy('total')
                ->get(), 720);
        }

        $valores = $this->cache->get($cacheKeyValores);


        //exclui o cache. Utilizar apenas para testes.
        $this->cache->forget($cacheKeyArea);

        if(!$this->cache->has($cacheKeyArea)){
            $this->cache->put($cacheKeyArea, DB::table('valores_series')
                ->select(
                    DB::raw(
                        "  
                    ST_AsGeoJSON(ST_ConvexHull(ST_Collect($tabelas[$abrangencia].edterritorios_bounding_box)))  as bounding_box_total
                    "
                    ))
                ->join("$tabelas[$abrangencia]", 'valores_series.regiao_id', '=', "$tabelas[$abrangencia].edterritorios_codigo")
                ->where($where)
                /*->whereYear("$tabelas[$abrangencia].edterritorios_data_inicial", '<=', $periodo)
                ->whereYear("$tabelas[$abrangencia].edterritorios_data_final", '>=', $periodo)*/
                ->where("$tabelas[$abrangencia].edterritorios_data_inicial", '<=', $periodo)
                ->where("$tabelas[$abrangencia].edterritorios_data_final", '>=', $periodo)
                ->when($regions[0]!=0, function($query) use ($regions, $tabelas, $abrangencia){
                    return $query->whereIn("$tabelas[$abrangencia].edterritorios_codigo", $regions);
                })
                /*->whereIn("$tabelas[$abrangencia].edterritorios_codigo", $regions)*/

                ->get(), 720);
        }

        $area = $this->cache->get($cacheKeyArea);

        //Log::info("======================================================================");
	//Log::info(DB::getQueryLog());

        return $this->mountAreas($valores, $area);
    }

    private function mountAreas($valores, $area){
        $areas = [];
        $areas['type'] = 'FeatureCollection';
        $areas['features'] = [];
        foreach($valores as $index => $valor){
            $areas['features'][$index]['type'] = 'Feature';
            $areas['features'][$index]['id'] = $index;
            $areas['features'][$index]['properties']['sigla'] = $valor->sigla;
            $areas['features'][$index]['properties']['nome'] = $valor->nome;
            $areas['features'][$index]['properties']['total'] = $valor->total;
            $areas['features'][$index]['properties']['x'] = $valor->x;
            $areas['features'][$index]['properties']['y'] = $valor->y;
            $areas['features'][$index]['geometry'] = json_decode($valor->geometry);


            //$areas['features'][$index]['centro'] = $valor->centro_de_tudo;
        }

        $areas['bounding_box_total'] = [];
        $areas['bounding_box_total']['type'] = 'FeatureCollection';
        $areas['bounding_box_total']['features'] = [];


        $object_bounding_box_total = json_decode($area[0]->bounding_box_total);
        $bounding_box_total = $object_bounding_box_total->coordinates;
        $areas['bounding_box_total'] = $bounding_box_total;
        //$areas['bounding_box_total'] = null;



        return $areas;
    }



    /*function valoresRegiaoPorPeriodo($id, $tipoValores, $min, $max){

        //1 - Numérico Incremental / 2 - Numérico Agregado / 3 - Taxa
        if($tipoValores==2){
            $valores = DB::table('valores_series')
                ->select(DB::raw("valores_series.valor as total, valores_series.uf, ed_territorios_uf.edterritorios_nome as nome"))
                ->join('ed_territorios_uf', 'valores_series.uf', '=', 'ed_territorios_uf.edterritorios_sigla')
                ->where([
                    ['valores_series.serie_id', $id],
                    ['valores_series.periodo', $max]
                ])
                ->groupBy('valores_series.uf', 'ed_territorios_uf.edterritorios_nome', 'valores_series.valor')
                ->orderBy('valores_series.uf')
                ->get();


            return $valores;
        }

        $valores = DB::table('valores_series')
            ->select(DB::raw("sum(valores_series.valor) as total, valores_series.uf, ed_territorios_uf.edterritorios_nome as nome"))
            ->join('ed_territorios_uf', 'valores_series.uf', '=', 'ed_territorios_uf.edterritorios_sigla')
            ->where([
                ['valores_series.serie_id', $id],
                ['valores_series.periodo', '>=', $min],
                ['valores_series.periodo', '<=', $max]
            ])
            ->groupBy('valores_series.uf', 'ed_territorios_uf.edterritorios_nome')
            ->orderBy('valores_series.uf')
            ->get();


        return $valores;
    }*/



    function valoresInicialFinalRegiaoPorPeriodo($id, $min, $max, $regions, $abrangencia){

        $regions = explode(',', $regions);

        $tabelas = [
            1 => 'spat.ed_territorios_paises',
            2 => 'spat.ed_territorios_regioes',
            3 => 'spat.ed_territorios_uf',
            4 => 'spat.ed_territorios_municipios',
            5 => 'spat.ed_territorios_microrregioes',
            6 => 'spat.ed_territorios_mesoregioes',
            7 => 'spat.ed_territorios_piaui_tds'
        ];

        DB::enableQueryLog();

        $valores = DB::table('valores_series')
            ->select(DB::raw("valores_series.valor, valores_series.periodo, $tabelas[$abrangencia].edterritorios_sigla as sigla, $tabelas[$abrangencia].edterritorios_nome as nome"))
            ->join($tabelas[$abrangencia], 'valores_series.regiao_id', '=', "$tabelas[$abrangencia].edterritorios_codigo")
            ->where('valores_series.serie_id', $id)
            ->where('valores_series.tipo_regiao', $abrangencia)
            ->where(function ($query) use ($min, $max) {
                $query->where('valores_series.periodo', $min)
                    ->orWhere('valores_series.periodo', $max);
            })
            ->whereIn("$tabelas[$abrangencia].edterritorios_codigo", $regions)
            ->orderBy(DB::raw("$tabelas[$abrangencia].edterritorios_sigla, valores_series.periodo"))
            ->get();

        Log::info(DB::getQueryLog());

        //dd($valores);

        return $valores;
    }

    /*function valoresSeriesRegiaoPorPeriodo($min, $max){
        $valores = DB::table('valores_series')
            ->select(DB::raw("sum(valores_series.valor) as total, valores_series.uf"))
            ->join('ed_territorios_uf', 'valores_series.uf', '=', 'ed_territorios_uf.edterritorios_sigla')
            ->where([
                ['valores_series.serie_id', 1],
                ['valores_series.periodo', '>=', $min],
                ['valores_series.periodo', '<=', $max]
            ])
            ->groupBy('valores_series.uf')
            ->orderBy('valores_series.uf')
            ->get();

        return $valores;
    }*/

    function valoresPeriodoPorRegiao($id, $min, $max){
        $valores = DB::table('valores_series')
        ->select(DB::raw("valores_series.valor as total, valores_series.periodo"))
            ->join('ed_territorios_uf', 'valores_series.uf', '=', 'ed_territorios_uf.edterritorios_sigla')
            ->where([
                ['valores_series.serie_id', $id],
                ['valores_series.periodo', '>=', $min],
                ['valores_series.periodo', '<=', $max]
            ])
            ->groupBy('valores_series.periodo')
            ->orderBy('valores_series.periodo')
            ->get();

        return $valores;
    }


    function valoresGeometryUf($id, $min, $max){

        $valores = DB::table('valores_series')
            ->select(DB::raw("ST_AsGeoJSON(ed_territorios_uf.edterritorios_geometry) as geometry, valores_series.valor, valores_series.uf"))
            ->join('ed_territorios_uf', 'valores_series.uf', '=', 'ed_territorios_uf.edterritorios_sigla')
            ->join('series', 'series.id', '=', 'valores_series.serie_id')
            ->where([
                ['valores_series.serie_id', $id],
                ['valores_series.periodo', '>=', $min],
                ['valores_series.periodo', '<=', $max]
            ])
            ->get();

        $areas = [];
        $areas['type'] = 'FeatureCollection';
        $areas['features'] = [];
        foreach($valores as $index => $valor){
            $areas['features'][$index]['type'] = 'Feature';
            $areas['features'][$index]['id'] = $index;
            $areas['features'][$index]['properties']['uf'] = $valor->uf;
            $areas['features'][$index]['properties']['valor'] = $valor->total;
            $areas['features'][$index]['geometry'] = json_decode($valor->geometry);
        }

        //dd($areas);

        return $areas;
    }

    function regioes($id){
        $regioes = DB::table('valores_series')
            ->select('uf')
            ->where('serie_id', $id)
            ->distinct()
            ->orderBy('uf')
            ->get();

        return $regioes;
    }

    function map($id, $periodo, $regions, $abrangencia){

        $lang =  App::getLocale();

        $serie = \App\Serie::select('textos_series.id', 'textos_series.titulo as titulo', 'unidades.tipo as tipo_unidade', 'series.periodicidade_id')
            ->join('textos_series', 'textos_series.serie_id', '=', 'series.id')
            ->join('unidades', 'unidades.id', '=', 'series.unidade')
            ->where('textos_series.idioma_sigla', $lang)
            ->first();

        Log::info([$serie]);

        return view('serie.map', [
            'id' => $id,
            'serie' => $serie->titulo,
            'tipoUnidade' => $serie->tipo_unidade,
            'periodicidade' => $serie->periodicidade_id,
            'periodo' => $periodo,
            'regions' => $regions,
            'abrangencia' => $abrangencia,
        ]);
    }

}
