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
                //->paginate(10);
                ->paginate(10);
        }

        $parametros = "";
        if($origem_id != ""){
            $parametros .= "/$origem_id";
        }
        if(!empty($origem_titulo)){
            $parametros .= "/$origem_titulo";
        }
        $paginateUrl = env('APP_PROTOCOL').config('app.url').'/artigos'.$parametros;
        $artigos->setPath($paginateUrl);

        $menus = DB::table('links')->where('idioma_sigla', $lang)->get();

        if($origem_id==0){
            $authors = DB::table('authors')->orderBy('titulo')->get();
        }else{
            $authors = DB::table('authors')
                ->select('authors.*')
                ->join('author_artigo', 'authors.id', '=', 'author_artigo.author_id')
                ->join('artigos', 'artigos.id', '=', 'author_artigo.artigo_id')
                ->where('artigos.origem_id', '=', $origem_id)
                ->orderBy('authors.titulo')
                ->distinct()
                ->get();
        }

        $fontes = \App\Fonte::lists('titulo', 'id')->all();

        return view('artigo.listar', [
            'artigos' => $artigos,
            'menus' => $menus,
            'origem_id' => $origem_id,
            'authors' => $authors,
            "fontes" => $fontes,
            'origem_titulo' => $origem_titulo,
            'autor_id' => $autor_id,
            'autor_titulo' => $autor_titulo,
            'valorBusca' => 0
        ]);
    }

    public function listar2($origem_id, $origem_titulo, $autor_id=0, $autor_titulo=0){

        /*$origem_id = $request->origin_id;
        $origem_titulo = $request->origem_titulo;
        $autor_id = $request->autor_id;
        $autor_titulo = $request->autor_titulo;*/

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
                //->paginate(10);
                ->paginate(10);
        }

        $parametros = "";
        if($origem_id != ""){
            $parametros .= "/$origem_id";
        }
        if(!empty($origem_titulo)){
            $parametros .= "/$origem_titulo";
        }
        $paginateUrl = env('APP_PROTOCOL').config('app.url').'/artigos'.$parametros;
        $artigos->setPath($paginateUrl);


        $menus = DB::table('assuntos')
            ->join('idiomas_assuntos', 'idiomas_assuntos.assunto_id', '=', 'assuntos.id')
            ->where('idiomas_assuntos.idioma_sigla', $lang)
            ->get();

        if($origem_id==0){
            $authors = DB::table('authors')->orderBy('titulo')
                ->join('author_artigo', 'authors.id', '=', 'author_artigo.author_id')
                ->get();
        }else{
            $authors = DB::table('authors')
                ->select('authors.*')
                ->join('author_artigo', 'authors.id', '=', 'author_artigo.author_id')
                ->join('artigos', 'artigos.id', '=', 'author_artigo.artigo_id')
                ->where('artigos.origem_id', '=', $origem_id)
                ->orderBy('authors.titulo')
                ->distinct()
                ->get();
        }

        $fontes = \App\Fonte::select('titulo', 'id')->get();

        return view('artigo.listar-atlas-vl', [
            'artigos' => $artigos,
            'menus' => $menus,
            'origem_id' => $origem_id,
            'authors' => $authors,
            "fontes" => $fontes,
            'origem_titulo' => $origem_titulo,
            'autor_id' => $autor_id,
            'autor_titulo' => $autor_titulo,
            'valorBusca' => 0
        ]);
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

	$lang =  App::getLocale();

        $dados = $request->all();

        $busca = new \stdClass();
        $busca->titulo = $dados['busca'];
        $busca->descricao = '';

       $valorBusca = $dados['busca'];

        if($origem_id==0){
            $artigos = DB::table('artigos')
                ->orderBy('titulo')
                ->where([
                ['titulo', 'ilike', "%$busca->titulo%"]
            ])
                //->paginate(15);
                ->paginate(10);
        }else{
            $artigos = DB::table('artigos')
                ->orderBy('titulo')
                ->where([
                ['titulo', 'ilike', "%$busca->titulo%"]
            ])
                ->where('origem_id', '=', $origem_id )
                //->paginate(15);
                ->paginate(10);
        }

        $parametros = "";
        if($origem_id != ""){
            $parametros .= "/$origem_id";
        }
        $paginateUrl = env('APP_PROTOCOL').config('app.url').'/artigos'.$parametros."/lista";
        $artigos->setPath($paginateUrl);


        $menus = DB::table('links')->where('idioma_sigla', $lang)->get();
        $authors = DB::table('authors')->orderBy('titulo')->get();

        $origem_titulo = "";


        return view('artigo.listar', ['artigos' => $artigos, 'tipos' => $busca, 'menus' => $menus, 'origem_id' => $origem_id, 'authors' => $authors, 'origem_titulo' => $origem_titulo, 'autor_id' => 0, 'autor_titulo' => 0, 'valorBusca' => $valorBusca]);

    }

    public function buscar2(Request $request, $origem_id){

        $lang =  App::getLocale();

        $dados = $request->all();

        $busca = new \stdClass();
        $busca->titulo = $dados['busca'];
        $busca->descricao = '';

        $valorBusca = $dados['busca'];

        if($origem_id==0){
            $artigos = DB::table('artigos')
                ->orderBy('titulo')
                ->where([
                    ['titulo', 'ilike', "%$busca->titulo%"]
                ])
                ->whereYear('data', $dados['ano'])
                ->paginate(10);
        }else{
            $artigos = DB::table('artigos')
                ->orderBy('titulo')
                ->where([
                    ['titulo', 'ilike', "%$busca->titulo%"]
                ])
                ->whereYear('data', $dados['ano'])
                ->where('origem_id', '=', $origem_id )
                ->paginate(10);
        }

        $parametros = "";
        if($origem_id != ""){
            $parametros .= "/$origem_id";
        }
        $paginateUrl = env('APP_PROTOCOL').config('app.url').'/artigos'.$parametros."/lista";
        $artigos->setPath($paginateUrl);


        $menus = DB::table('links')->where('idioma_sigla', $lang)->get();
        $authors = DB::table('authors')->orderBy('titulo')->get();

        $origem_titulo = "";


        return view('artigo.listar', ['artigos' => $artigos, 'tipos' => $busca, 'menus' => $menus, 'origem_id' => $origem_id, 'authors' => $authors, 'origem_titulo' => $origem_titulo, 'autor_id' => 0, 'autor_titulo' => 0, 'valorBusca' => $valorBusca]);

    }
}
