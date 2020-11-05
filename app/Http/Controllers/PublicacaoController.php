<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class PublicacaoController extends Controller
{
    public function listar($id=0, $titulo=''){

        $lang =  App::getLocale();

        $publicacao = DB::table('artigos')
            ->where('idioma_sigla', $lang)
            ->where('publicacao_atlas', 1)
            ->when($id > 0, function ($query) use ($id){
                return $query->where('id', $id);
            })
            ->orderBy('id', 'desc')
            ->first();

        $publicacoes = DB::table('artigos')
            ->where('idioma_sigla', $lang)
            ->where('publicacao_atlas', 1)
            ->where('artigos.id', '!=', $publicacao->id)
            ->orderBy('id', 'desc')
            ->paginate(20);

        $authors = DB::table('authors')
            ->select('authors.*')
            ->join('author_artigo', 'authors.id', '=', 'author_artigo.author_id')
            ->join('artigos', 'artigos.id', '=', 'author_artigo.artigo_id')
            ->where('author_artigo.artigo_id', '=', $publicacao->id)
            ->orderBy('authors.titulo')
            ->distinct()
            ->get();

        return view('publicacoes.listar', ['publicacao' => $publicacao, 'publicacoes' => $publicacoes, 'authors' => $authors]);
    }
}
