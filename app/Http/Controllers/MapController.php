<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\DB;

class MapController extends Controller
{
    public function index($id){

        $serie = \App\Serie::find($id);

        return view('map', ['id' => $id, 'series' => $serie]);
    }

    /*public function getData(){
        //$data = DB::table('ed_territorios_uf')->select(DB::raw("ST_AsGeoJSON(ST_Transform(edterritorios_centroide,4326))"))->get();
        //$data = DB::table('ed_territorios_uf')->select(DB::raw("edterritorios_centroide"))->get();
        //$data = DB::table('ed_territorios_uf')->select(DB::raw("ST_X(edterritorios_centroide), ST_Y(edterritorios_centroide)"))->get();
        //$data = DB::table('ed_territorios_uf')->select(DB::raw("ST_AsText(edterritorios_geometry)"))->get();
        //$data = DB::table('ed_territorios_uf')->select(DB::raw("ST_AsGeoJSON(ST_Transform(edterritorios_geometry,4326))"))->get();
        //$data = DB::table('ed_territorios_uf')->select(DB::raw("ST_AsGeoJSON(edterritorios_geometry)"))->get();
        $areas = DB::table('valores_series')
            ->select(DB::raw("ST_AsGeoJSON(ed_territorios_uf.edterritorios_geometry), valores_series.valor"))
            ->join('ed_territorios_uf', 'valores_series.uf', '=', 'ed_territorios_uf.edterritorios_sigla')
            ->get();

        $circles = DB::table('valores_series')
            ->select(DB::raw("ST_X(edterritorios_centroide), ST_Y(edterritorios_centroide), valores_series.valor"))
            ->join('ed_territorios_uf', 'valores_series.uf', '=', 'ed_territorios_uf.edterritorios_sigla')
            ->get();
        $circles = [];

        $series = DB::table('series')->where('id', 1)->get();

        $areas = DB::table('valores_series')
            ->select(DB::raw("ST_AsGeoJSON(ed_territorios_uf.edterritorios_geometry), valores_series.valor, valores_series.periodo"))
            ->join('ed_territorios_uf', 'valores_series.uf', '=', 'ed_territorios_uf.edterritorios_sigla')
            ->get();


        $data = ['circles' => $circles, 'areas' => $areas, 'series' => $series];

        return $data;
    }*/

    function periodos($id){
        $periodos = DB::table('valores_series')
            ->select('periodo')
            ->distinct('periodo')
            ->where('serie_id', $id)
            ->orderBy('periodo')
            ->get();

        $retorno = [];
        foreach($periodos as $index => $periodo){
            $retorno[$index] = $periodo->periodo;
        }

        return $retorno;
    }

    function valoresRegiaoPorPeriodoGeometry($id, $min, $max){
        $valores = DB::table('valores_series')
            ->select(DB::raw("ST_AsGeoJSON(ed_territorios_uf.edterritorios_geometry) as geometry, sum(valores_series.valor) as total, valores_series.uf"))
            ->join('ed_territorios_uf', 'valores_series.uf', '=', 'ed_territorios_uf.edterritorios_sigla')
            ->where([
                ['valores_series.serie_id', $id],
                ['valores_series.periodo', '>=', $min],
                ['valores_series.periodo', '<=', $max]
            ])
            ->groupBy('valores_series.uf', 'ed_territorios_uf.edterritorios_geometry')
            ->orderBy('total')
            ->get();

        $areas = [];
        $areas['type'] = 'FeatureCollection';
        $areas['features'] = [];
        foreach($valores as $index => $valor){
            $areas['features'][$index]['type'] = 'Feature';
            $areas['features'][$index]['id'] = $index;
            $areas['features'][$index]['properties']['uf'] = $valor->uf;
            $areas['features'][$index]['properties']['total'] = $valor->total;
            $areas['features'][$index]['geometry'] = json_decode($valor->geometry);
        }


        return $areas;
    }

    function valoresRegiaoPorPeriodo($id, $min, $max){
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

    function valoresInicialFinalRegiaoPorPeriodo($id, $min, $max){
        $valores = DB::table('valores_series')
            ->select(DB::raw("valores_series.valor, valores_series.periodo, valores_series.uf, ed_territorios_uf.edterritorios_nome as nome"))
            ->join('ed_territorios_uf', 'valores_series.uf', '=', 'ed_territorios_uf.edterritorios_sigla')
            ->where('valores_series.serie_id', $id)
            ->where(function ($query) use ($min, $max) {
                $query->where('valores_series.periodo', $min)
                    ->orWhere('valores_series.periodo', $max);
            })
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

}
