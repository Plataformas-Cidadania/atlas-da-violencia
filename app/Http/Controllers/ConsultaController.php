<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class ConsultaController extends Controller
{
    public function index($id = 0, $tema = null){

        $lang = \App::getLocale();

        $content = \App\Quemsomo::where([
            ['idioma_sigla', $lang],
            ['tipo', 9]// 9 - Consultas
        ])->first();

        return view('consulta.listar', ['id' => $id, 'content' => $content]);
    }

    public function listing(Request $request){

        $lang = \App::getLocale();

        $parameters = $request->parameters;

        $consultas = \App\Consulta::select('consultas.id', 'idiomas_consultas.titulo as titulo')
            //->join('unidades', 'consultas.unidade_id', '=', 'unidades.id')
            //->join('periodicidades', 'consultas.periodicidade_id', '=', 'periodicidades.id')
            ->join('idiomas_consultas', 'idiomas_consultas.consulta_id', '=', 'consultas.id')
            ->where([
                ['idiomas_consultas.idioma_sigla', $lang],
                ['idiomas_consultas.titulo', 'ilike', "%".$parameters['search']."%"],
            ])
            ->when($parameters['tema_id']!=0, function($query) use ($parameters){
                return $query->where('tema_id', $parameters['tema_id']);
            })
            ->paginate($parameters['limit']);

        return $consultas;
    }

    public function detail($id){
        $lang = \App::getLocale();

        $consulta = \App\Consulta::select('idiomas_consultas.titulo', 'consultas.url', 'consultas.arquivo')
            ->join('idiomas_consultas', 'idiomas_consultas.consulta_id', '=', 'consultas.id')
            ->find($id);

        return view('consulta.detalhar', ['consulta' => $consulta]);
    }
}
