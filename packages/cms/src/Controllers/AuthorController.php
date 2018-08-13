<?php

namespace Cms\Controllers;

use Cms\Models\ImagemCms;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Facades\Image;

class AuthorController extends Controller
{
    
    

    public function __construct()
    {
        $this->author = new \App\Author;
        $this->campos = [
            'imagem', 'titulo', 'cmsuser_id',
        ];
        $this->pathImagem = public_path().'/imagens/authors';
        $this->sizesImagem = [
            'xs' => ['width' => 140, 'height' => 79],
            'sm' => ['width' => 480, 'height' => 270],
            'md' => ['width' => 580, 'height' => 326],
            'lg' => ['width' => 1170, 'height' => 658]
        ];
        $this->widthOriginal = true;
    }

    function index()
    {

        $authors = \App\Author::all();
        //$series = \App\Serie::lists('titulo', 'id')->all();

        return view('cms::author.listar', ['authors' => $authors]);
    }

    public function listar(Request $request)
    {

        //Log::info('CAMPOS: '.$request->campos);

        //Auth::loginUsingId(2);

        $campos = explode(", ", $request->campos);

        $authors = DB::table('authors')
            ->select($campos)
            ->where([
                [$request->campoPesquisa, 'like', "%$request->dadoPesquisa%"],
            ])
            ->orderBy($request->ordem, $request->sentido)
            ->paginate($request->itensPorPagina);
        return $authors;
    }

    public function inserir(Request $request)
    {

        $data = $request->all();

        $data['author'] += ['cmsuser_id' => auth()->guard('cms')->user()->id];//adiciona id do usuario

        //verifica se o index do campo existe no array e caso não exista inserir o campo com valor vazio.
        foreach($this->campos as $campo){
            if(!array_key_exists($campo, $data)){
                $data['author'] += [$campo => ''];
            }
        }

        $file = $request->file('file');

        if($file!=null){
            $filename = rand(1000,9999)."-".clean($file->getClientOriginalName());
            $imagemCms = new ImagemCms();
            $success = $imagemCms->inserir($file, $this->pathImagem, $filename, $this->sizesImagem, $this->widthOriginal);
            
            if($success){
                $data['author']['imagem'] = $filename;
                return $this->author->create($data['author']);
            }else{
                return "erro";
            }
        }

        return $this->author->create($data['author']);

    }

    public function detalhar($id)
    {
        $author = $this->author->where([
            ['id', '=', $id],
        ])->firstOrFail();
        //$series = \App\Serie::lists('titulo', 'id')->all();

        return view('cms::author.detalhar', ['author' => $author/*, 'series' => $series*/]);
    }

    public function alterar(Request $request, $id)
    {
        $data = $request->all();
        $data['author'] += ['cmsuser_id' => auth()->guard('cms')->user()->id];//adiciona id do usuario

        //verifica se o index do campo existe no array e caso não exista inserir o campo com valor vazio.
        foreach($this->campos as $campo){
            if(!array_key_exists($campo, $data)){
                if($campo!='imagem'){
                    $data['author'] += [$campo => ''];
                }
            }
        }
        $author = $this->author->where([
            ['id', '=', $id],
        ])->firstOrFail();

        $file = $request->file('file');

        if($file!=null){
            $filename = rand(1000,9999)."-".clean($file->getClientOriginalName());
            $imagemCms = new ImagemCms();
            $success = $imagemCms->alterar($file, $this->pathImagem, $filename, $this->sizesImagem, $this->widthOriginal, $author);
            if($success){
                $data['author']['imagem'] = $filename;
                $author->update($data['author']);
                return $author->imagem;
            }else{
                return "erro";
            }
        }

        //remover imagem
        if($data['removerImagem']){
            $data['author']['imagem'] = '';
            if(file_exists($this->pathImagem."/".$author->imagem)) {
                unauthor($this->pathImagem . "/" . $author->imagem);
            }
        }

        $author->update($data['author']);
        return "Gravado com sucesso";
    }

    public function excluir($id)
    {
        //Auth::loginUsingId(2);

        $author = $this->author->where([
            ['id', '=', $id],
        ])->firstOrFail();

        //remover imagens        
        if(!empty($author->imagem)){
            //remover imagens
            $imagemCms = new ImagemCms();
            $imagemCms->excluir($this->pathImagem, $this->sizesImagem, $author);
        }

        $author->delete();

    }

    


}
