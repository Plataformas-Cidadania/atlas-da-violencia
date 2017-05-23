<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\DB;

class VideoController extends Controller
{
    public function listar(){
        $videos = DB::table('videos')->orderBy('posicao', 'desc')->paginate(10);
       
        return view('video.listar', ['videos' => $videos]);
    }
    public function buscar(Request $request){

        $dados = $request->all();

        $busca = new \stdClass();
        $busca->titulo = $dados['busca'];
        $busca->tags = $dados['busca'];
        $busca->descricao = '';

        //return $dados['busca'];


        $videos = DB::table('videos')->where([
            ['titulo', 'ilike', "%$busca->titulo%"]
        ])->orWhere([
            ['tags', 'ilike', "%$busca->tags%"]
        ])->paginate(10);

        return view('video.listar', ['videos' => $videos, 'tipos' => $busca]);

    }
}
