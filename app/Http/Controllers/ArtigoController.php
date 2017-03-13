<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\DB;

class ArtigoController extends Controller
{
    public function listar($origem_id, $origem_titulo, $autor_id=0){

        $where = [];

        if($origem_id!=0) {
            array_push($where, ['origem_id', '=', $origem_id]) ;
        }
        if(!empty($autor_id)) {
            array_push($where, ['autor', '=', $autor_id]);
        }

        if(count($where)==0){
            $artigos = DB::table('artigos')->paginate(10);
        }else{
            $artigos = DB::table('artigos')->where($where)->paginate(10);
        }


        $menus = DB::table('links')->get();
        $authors = DB::table('authors')->orderBy('titulo')->get();

        return view('artigo.listar', ['artigos' => $artigos, 'menus' => $menus, 'origem_id' => $origem_id, 'authors' => $authors, 'origem_titulo' => $origem_titulo]);
    }
    public function detalhar($id){

        $artigo = new \App\Artigo;
        $artigo = $artigo->find($id);

        return view('artigo.detalhar', ['artigo' => $artigo]);
        
    }
    public function buscar(Request $request, $origem_id){

        $dados = $request->all();

        $busca = new \stdClass();
        $busca->titulo = $dados['busca'];
        $busca->descricao = '';

        /*$artigos = DB::table('artigos')->where([
            ['titulo', 'like', "%$busca->titulo%"]
        ])->paginate(10);*/

        if($origem_id==0){
            $artigos = DB::table('artigos')
                ->where([
                ['titulo', 'like', "%$busca->titulo%"]
            ])
                ->paginate(15);
        }else{
            $artigos = DB::table('artigos')
                ->where([
                ['titulo', 'like', "%$busca->titulo%"]
            ])
                ->where('origem_id', '=', $origem_id )
                ->paginate(15);
        }
        $menus = DB::table('links')->get();
        $authors = DB::table('authors')->orderBy('titulo')->get();

        $origem_titulo = "";


        return view('artigo.listar', ['artigos' => $artigos, 'tipos' => $busca, 'menus' => $menus, 'origem_id' => $origem_id, 'authors' => $authors, 'origem_titulo' => $origem_titulo]);

    }
}
