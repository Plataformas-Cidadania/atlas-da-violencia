<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ArtigoController extends Controller
{
    public function listar($origem_id, $origem_titulo, $autor_id=0, $autor_titulo=0){

        $lang =  App::getLocale();

        $where = [];

        if($origem_id!=0) {
            array_push($where, ['artigos.origem_id', '=', $origem_id]) ;
        }
        if(!empty($autor_id)) {
            array_push($where, ['author_artigo.author_id', '=', $autor_id]);
        }

        if(count($where)==0){
            $artigos = DB::table('artigos')->where('idioma_sigla', $lang)->orderBy('id', 'desc')->paginate(10);
        }else{

            $artigos = DB::table('artigos')
                ->join('author_artigo', 'artigos.id', '=', 'author_artigo.artigo_id')
                ->where($where)
                ->where('idioma_sigla', $lang)
                ->select('artigos.*')
                ->orderBy('artigos.id', 'desc')
                ->distinct()
                ->paginate(10);
        }




        $menus = DB::table('links')->get();

        $authors = DB::table('authors')->orderBy('titulo')->get();

        return view('artigo.listar', ['artigos' => $artigos, 'menus' => $menus, 'origem_id' => $origem_id, 'authors' => $authors, 'origem_titulo' => $origem_titulo, 'autor_id' => $autor_id, 'autor_titulo' => $autor_titulo]);
    }
    public function detalhar($id){

        $artigo = new \App\Artigo;
        $artigo = $artigo->find($id);


        $autores = DB::table('authors')
            ->join('author_artigo', 'authors.id', '=', 'author_artigo.author_id')
            ->where('author_artigo.artigo_id', $id)
            ->select('authors.*')
            ->get();


        return view('artigo.detalhar', ['artigo' => $artigo, 'autores' => $autores]);
        
    }
    public function buscar(Request $request, $origem_id){

        $dados = $request->all();

        $busca = new \stdClass();
        $busca->titulo = $dados['busca'];
        $busca->descricao = '';


        if($origem_id==0){
            $artigos = DB::table('artigos')
                ->orderBy('titulo')
                ->where([
                ['titulo', 'like', "%$busca->titulo%"]
            ])
                ->paginate(15);
        }else{
            $artigos = DB::table('artigos')
                ->orderBy('titulo')
                ->where([
                ['titulo', 'like', "%$busca->titulo%"]
            ])
                ->where('origem_id', '=', $origem_id )
                ->paginate(15);
        }
        $menus = DB::table('links')->get();
        $authors = DB::table('authors')->orderBy('titulo')->get();

        $origem_titulo = "";


        return view('artigo.listar', ['artigos' => $artigos, 'tipos' => $busca, 'menus' => $menus, 'origem_id' => $origem_id, 'authors' => $authors, 'origem_titulo' => $origem_titulo, 'autor_id' => 0, 'autor_titulo' => 0]);

    }
}
