<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\DB;

class SerieController extends Controller
{
    public function listar(){
        //$pages = DB::table('pages')->paginate(10);
       
        return view('serie.listar');
        //return view('page.listar', ['pages' => $pages]);
    }
    public function detalhar(/*$id*/){

        /*$page = new \App\Page;
        $page = $page->find($id);
        return view('page.detalhar', ['page' => $page]);*/

        return view('serie.detalhar');

    }

    public function filtro(){
        return view('serie.filtro');
    }

    public function filtros($id, $titulo){

        $serie = \App\Serie::find($id);

        return view('serie.filtros', ['serie' => $serie, 'id' => $id, 'titulo' => $titulo]);
    }

    public function listarSeries(Request $request){

        $series = DB::table('series')
            ->select(DB::raw('series.*, min(valores_series.periodo) as min, max(valores_series.periodo) as max'))
            ->join('valores_series', 'valores_series.serie_id', '=', 'series.id')
            ->where('series.titulo', 'ilike', "%$request->search%")
            ->groupBy('series.id')
            ->get();

        return $series;
    }

    public function listarSeriesRelacionadas(Request $request){

        $parameters = $request->parameters;

        $series = DB::table('series')
            ->select(DB::raw('series.*, min(valores_series.periodo) as min, max(valores_series.periodo) as max'))
            ->join('valores_series', 'valores_series.serie_id', '=', 'series.id')
            ->where('series.id', $parameters['id'])
            ->orWhere('series.serie_id', $parameters['id'])
            ->groupBy('series.id')
            ->get();

        return $series;
    }

    public function dataSeries(Request $request){
        $serie = \App\Serie::find($request->id);

        //$regions = explode(',', $request->regions);

        return view('data-series', [
            'id' => $request->id,
            'series' => $serie,
            'from' => $request->from,
            'to' => $request->to,
            'regions' => $request->regions
        ]);
    }

    function valoresRegiaoUltimoPeriodo($id, $max){

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

    function valoresPeriodoRegioesSelecionadas($id, $min, $max, $regions){

        $regions = explode(',', $regions);

        $rows = DB::table('valores_series')
            ->select(DB::raw("valores_series.uf, valores_series.valor, valores_series.periodo"))
            ->join('ed_territorios_uf', 'valores_series.uf', '=', 'ed_territorios_uf.edterritorios_sigla')
            ->where([
                ['valores_series.serie_id', $id],
                ['valores_series.periodo', '>=', $min],
                ['valores_series.periodo', '<=', $max]
            ])
            ->whereIn('valores_series.uf', $regions)
            ->orderBy('valores_series.periodo')
            ->get();

        $data = [];

        foreach($rows as $row){
            $data[$row->uf][$row->periodo] = $row->valor;
        }

        return $data;
    }


}
