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

    function valoresRegiaoUltimoPeriodoGeometry($id, $max, $regions, $typeRegion, $typeRegionSerie){

        //1 - Numérico Incremental / 2 - Numérico Agregado / 3 - Taxa

        //ST_X(edterritorios_centroide), ST_Y(edterritorios_centroide)

        $regions = explode(',', $regions);

        $where = [
            ['valores_series.serie_id', $id],
            ['valores_series.periodo', $max]
        ];

        if($typeRegion=='region'){
            return $this->porRegiao($id, $max, $regions, $typeRegionSerie, $where);
        }

        if($typeRegion=='uf'){
            return $this->porUf($id, $max, $regions, $typeRegionSerie, $where);
        }

        if($typeRegion=='municipio'){
            return $this->porMunicipio($id, $max, $regions, $typeRegionSerie, $where);
        }


        /////////////////////////////////////////////////////////////////////////////
        /////////////////////////////////////////////////////////////////////////////
        /////////////////////////////////////////////////////////////////////////////
        /////////////////////////////////////////////////////////////////////////////
        /////////////////////////////////////////////////////////////////////////////
        /////////////////////////////////////////////////////////////////////////////
        /////////////////////////////////////////////////////////////////////////////

        $tablesRegion = '';
        if($typeRegion=='regiao'){
            $this->porUf($id, $max, $typeRegion);
            $tablesRegion = 'ed_territorios_regiao';
        }
        if($typeRegion=='uf'){
            $tablesRegion = 'ed_territorios_uf';
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
            ->join("ed_territorios_uf", 'valores_series.uf', '=', "ed_territorios_uf.edterritorios_sigla")
            ->where($where)
            ->groupBy('valores_series.uf', "ed_territorios_uf.edterritorios_geometry", "ed_territorios_uf.edterritorios_centroide", "ed_territorios_uf.edterritorios_nome")
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

    private function porRegiao($id, $max, $regions, $typerRegionSerie, $where){

        DB::connection()->enableQueryLog();

        if($typerRegionSerie==1){//1 - regiao
            $valores = DB::table('valores_series')
                ->select(
                    DB::raw(
                        "
                    ST_AsGeoJSON(ed_territorios_uf.edterritorios_geometry) as geometry, 
                    sum(valores_series.valor) as total, 
                    ed_territorios_regioes.edterritorios_sigla as sigla,
                    ed_territorios_regioes.edterritorios_nome as nome, 
                    ST_X(ed_territorios_uf.edterritorios_centroide) as x, 
                    ST_Y(ed_territorios_uf.edterritorios_centroide) as y
                    "
                    ))
                ->join("ed_territorios_regioes", 'valores_series.regiao_id', '=', "ed_territorios_regioes.edterritorios_codigo")
                ->where($where)
                ->groupBy("ed_territorios_regioes.edterritorios_sigla", "ed_territorios_regioes.edterritorios_nome", "ed_territorios_regioes.edterritorios_geometry", "ed_territorios_regioes.edterritorios_centroide")
                ->orderBy('total')
                ->get();

            return $this->mountAreas($valores);
        }

        //Caso os valores da série sejam separados por uf e a pesquisa seja por região
        if($typerRegionSerie==2){//2 - uf

            $where = [
                ['serie_id', $id],
                ['periodo', $max]
            ];


            $valores = DB::table('regions_by_uf')
                ->select(DB::raw(
                    "geometry, sum(valor) as total, sigla, nome, x, y, codigo"
                ))
                ->where($where)
                ->whereIn('codigo', $regions)
                ->groupBy("geometry", "sigla", "nome", "x", "y", "codigo")
                ->get();

            return $this->mountAreas($valores);


            /*$valores = DB::table('valores_series')
                ->select(
                    DB::raw(
                        "
                    ST_AsGeoJSON(ed_territorios_regioes.edterritorios_geometry) as geometry, 
                    sum(valores_series.valor) as total, 
                    ed_territorios_regioes.edterritorios_sigla as sigla, 
                    ed_territorios_regioes.edterritorios_nome as nome, 
                    ST_X(ed_territorios_regioes.edterritorios_centroide) as x, 
                    ST_Y(ed_territorios_regioes.edterritorios_centroide) as y
                    "
                    ))
                ->join("ed_territorios_uf", 'valores_series.regiao_id', '=', "ed_territorios_uf.edterritorios_codigo")

                ->join('spat.ed_territorios_uf', 'spat.ed_territorios_uf.edterritorios_codigo', '=', 'public.valores_series.regiao_id')
                ->join('spat.ed_uf', 'spat.ed_uf.eduf_cd_uf', '=', 'spat.ed_territorios_uf.edterritorios_codigo')
                ->join('spat.territorio', 'spat.territorio.terregiao', '=', 'spat.ed_uf.edre_cd_regiao')
                ->join('spat.ed_territorios_regioes', 'spat.ed_territorios_regioes.edterritorios_terid', '=', 'spat.territorio.terid')

                ->where($where)
                ->groupBy("ed_territorios_regioes.edterritorios_sigla", "ed_territorios_regioes.edterritorios_nome", "ed_territorios_regioes.edterritorios_geometry", "ed_territorios_regioes.edterritorios_centroide")
                ->orderBy('total')
                ->get();*/

            //return $valores;
            //Log::info(DB::getQueryLog());

            //return $this->mountAreas($valores);
        }

        if($typerRegionSerie==2){

        }

    }


    private function porUf($id, $max, $regions, $typerRegionSerie, $where){

        DB::connection()->enableQueryLog();

        if($typerRegionSerie==2){//2 - uf
            $valores = DB::table('valores_series')
                ->select(
                    DB::raw(
                        "
                    ST_AsGeoJSON(ed_territorios_uf.edterritorios_geometry) as geometry, 
                    sum(valores_series.valor) as total, 
                    ed_territorios_uf.edterritorios_sigla as sigla,
                    ed_territorios_uf.edterritorios_nome as nome, 
                    ST_X(ed_territorios_uf.edterritorios_centroide) as x, 
                    ST_Y(ed_territorios_uf.edterritorios_centroide) as y
                    "
                    ))
                ->join("ed_territorios_uf", 'valores_series.regiao_id', '=', "ed_territorios_uf.edterritorios_codigo")
                ->where($where)
                ->whereIn('ed_territorios_uf.edterritorios_codigo', $regions)
                ->groupBy("ed_territorios_uf.edterritorios_sigla", "ed_territorios_uf.edterritorios_nome", "ed_territorios_uf.edterritorios_geometry", "ed_territorios_uf.edterritorios_centroide")
                ->orderBy('total')
                ->get();

            Log::info(DB::getQueryLog());

            return $this->mountAreas($valores);
        }
    }

    private function porMunicipio($id, $max, $regions, $typerRegionSerie, $where){
        if($typerRegionSerie==3){

        }
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



    function valoresInicialFinalRegiaoPorPeriodo($id, $min, $max, $regions){

        $regions = explode(',', $regions);

        $valores = DB::table('valores_series')
            ->select(DB::raw("valores_series.valor, valores_series.periodo, valores_series.uf as sigla, ed_territorios_uf.edterritorios_nome as nome"))
            ->join('ed_territorios_uf', 'valores_series.uf', '=', 'ed_territorios_uf.edterritorios_sigla')
            ->where('valores_series.serie_id', $id)
            ->where(function ($query) use ($min, $max) {
                $query->where('valores_series.periodo', $min)
                    ->orWhere('valores_series.periodo', $max);
            })
            ->whereIn('ed_territorios_uf.edterritorios_codigo', $regions)
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
