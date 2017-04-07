<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MapController extends Controller
{

    function periodos($id){
        $periodos = DB::table('valores_series')
            ->select('periodo')
            ->distinct('periodo')
            ->where('serie_id', $id)
            ->orderBy('periodo')           ->get();

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
                    sum(valores_series.valor) as total, 
                    valores_series.uf, 
                    ed_territorios_uf.edterritorios_nome as nome, 
                    ST_X(ed_territorios_uf.edterritorios_centroide) as x, 
                    ST_Y(ed_territorios_uf.edterritorios_centroide) as y
                    "
                ))
            ->join('ed_territorios_uf', 'valores_series.uf', '=', 'ed_territorios_uf.edterritorios_sigla')
            ->where($where)
            ->groupBy('valores_series.uf', 'ed_territorios_uf.edterritorios_geometry', 'ed_territorios_uf.edterritorios_centroide', 'ed_territorios_uf.edterritorios_nome')
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

    function valoresRegiaoUltimoPeriodoGeometry($id, $max, $regions, $territorio){

        //1 - Numérico Incremental / 2 - Numérico Agregado / 3 - Taxa

        //ST_X(edterritorios_centroide), ST_Y(edterritorios_centroide)

        $regions = explode(',', $regions);

        $tabelas = [
            1 => 'spat.ed_territorios_paises',
            2 => 'spat.ed_territorios_regioes',
            3 => 'spat.ed_territorios_uf',
            4 => 'spat.ed_territorios_municipios',
            5 => 'spat.ed_territorios_microrregioes',
            6 => 'spat.ed_territorios_mesoregioes'
        ];

        $where = [
            ['valores_series.serie_id', $id],
            ['valores_series.periodo', $max]
        ];

        $valores = DB::table('valores_series')
            ->select(
                DB::raw(
                    "
                    ST_AsGeoJSON($tabelas[$territorio].edterritorios_geometry) as geometry, 
                    sum(valores_series.valor) as total, 
                    $tabelas[$territorio].edterritorios_sigla as sigla,
                    $tabelas[$territorio].edterritorios_nome as nome, 
                    ST_X($tabelas[$territorio].edterritorios_centroide) as x, 
                    ST_Y($tabelas[$territorio].edterritorios_centroide) as y
                    "
                ))
            ->join("$tabelas[$territorio]", 'valores_series.regiao_id', '=', "$tabelas[$territorio].edterritorios_codigo")
            ->where($where)
            ->whereIn("$tabelas[$territorio].edterritorios_codigo", $regions)
            ->groupBy("$tabelas[$territorio].edterritorios_sigla", "$tabelas[$territorio].edterritorios_nome", "$tabelas[$territorio].edterritorios_geometry", "$tabelas[$territorio].edterritorios_centroide")
            ->orderBy('total')
            ->get();

        Log::info(DB::getQueryLog());

        return $this->mountAreas($valores);
    }

    private function mountAreas($valores){
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
        }

        return $areas;
    }



    function valoresRegiaoPorPeriodo($id, $tipoValores, $min, $max){

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
    }



    function valoresInicialFinalRegiaoPorPeriodo($id, $min, $max, $regions, $territorio){

        $regions = explode(',', $regions);

        $tabelas = [
            1 => 'spat.ed_territorios_paises',
            2 => 'spat.ed_territorios_regioes',
            3 => 'spat.ed_territorios_uf',
            4 => 'spat.ed_territorios_municipios',
            5 => 'spat.ed_territorios_microrregioes',
            6 => 'spat.ed_territorios_mesoregioes'
        ];

        $valores = DB::table('valores_series')
            ->select(DB::raw("valores_series.valor, valores_series.periodo, valores_series.uf as sigla, $tabelas[$territorio].edterritorios_nome as nome"))
            ->join($tabelas[$territorio], 'valores_series.uf', '=', "$tabelas[$territorio].edterritorios_sigla")
            ->where('valores_series.serie_id', $id)
            ->where(function ($query) use ($min, $max) {
                $query->where('valores_series.periodo', $min)
                    ->orWhere('valores_series.periodo', $max);
            })
            ->whereIn("$tabelas[$territorio].edterritorios_codigo", $regions)
            ->orderBy(DB::raw('valores_series.uf, valores_series.periodo'))
            ->get();

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
        ->select(DB::raw("sum(valores_series.valor) as total, valores_series.periodo"))
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

        dd($areas);

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

}
