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


}
