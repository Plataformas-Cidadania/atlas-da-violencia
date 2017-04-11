<?php

namespace Cms\Controllers;

use Cms\Models\ImagemCms;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Facades\Image;

class DownloadController extends Controller
{
    
    

    public function __construct()
    {
        $this->download = new \App\Download;
        $this->campos = [
            'imagem', 'origem_id', 'titulo', 'descricao', 'arquivo', 'cmsuser_id', 'idioma_id',
        ];
        $this->pathImagem = public_path().'/imagens/downloads';
        $this->sizesImagem = [
            'xs' => ['width' => 140, 'height' => 79],
            'sm' => ['width' => 480, 'height' => 270],
            'md' => ['width' => 580, 'height' => 326],
            'lg' => ['width' => 1170, 'height' => 658]
        ];
        $this->widthOriginal = true;

        $this->pathArquivo = public_path().'/arquivos/downloads';
    }

    function index()
    {

        $downloads = \App\Download::all();
        $series = \App\Serie::lists('titulo', 'id')->all();
        $idiomas = \App\Idioma::lists('titulo', 'id')->all();

        return view('cms::download.listar', ['downloads' => $downloads, 'series' => $series, 'idiomas' => $idiomas]);
    }

    public function listar(Request $request)
    {

        //Log::info('CAMPOS: '.$request->campos);

        //Auth::loginUsingId(2);

        $campos = explode(", ", $request->campos);

        $downloads = DB::table('downloads')
            ->select($campos)
            ->where([
                [$request->campoPesquisa, 'like', "%$request->dadoPesquisa%"],
            ])
            ->orderBy($request->ordem, $request->sentido)
            ->paginate($request->itensPorPagina);
        return $downloads;
    }

    /*public function inserir(Request $request)
    {

        $data = $request->all();

        $data['download'] += ['cmsuser_id' => auth()->guard('cms')->user()->id];//adiciona id do usuario

        //verifica se o index do campo existe no array e caso não exista inserir o campo com valor vazio.
        foreach($this->campos as $campo){
            if(!array_key_exists($campo, $data)){
                $data['download'] += [$campo => ''];
            }
        }

        $file = $request->file('file');

        if($file!=null){
            $filename = rand(1000,9999)."-".clean($file->getClientOriginalName());
            $imagemCms = new ImagemCms();
            $success = $imagemCms->inserir($file, $this->pathImagem, $filename, $this->sizesImagem, $this->widthOriginal);
            
            if($success){
                $data['download']['imagem'] = $filename;
                return $this->download->create($data['download']);
            }else{
                return "erro";
            }
        }

        return $this->download->create($data['download']);

    }*/

    public function inserir(Request $request)
    {

        $data = $request->all();

        $data['download'] += ['cmsuser_id' => auth()->guard('cms')->user()->id];//adiciona id do usuario

        //verifica se o index do campo existe no array e caso não exista inserir o campo com valor vazio.
        foreach($this->campos as $campo){
            if(!array_key_exists($campo, $data)){
                $data['download'] += [$campo => ''];
            }
        }

        $file = $request->file('file');
        $arquivo = $request->file('arquivo');


        $successFile = true;
        if($file!=null){
            $filename = rand(1000,9999)."-".clean($file->getClientOriginalName());
            $imagemCms = new ImagemCms();
            $successFile = $imagemCms->inserir($file, $this->pathImagem, $filename, $this->sizesImagem, $this->widthOriginal);
            if($successFile){
                $data['download']['imagem'] = $filename;
            }
        }

        $successArquivo = true;
        if($arquivo!=null){
            $filenameArquivo = rand(1000,9999)."-".clean($arquivo->getClientOriginalName());
            $successArquivo = $arquivo->move($this->pathArquivo, $filenameArquivo);
            if($successArquivo){
                $data['download']['arquivo'] = $filenameArquivo;
            }
        }


        if($successFile && $successArquivo){
            return $this->download->create($data['download']);
        }else{
            return "erro";
        }


        return $this->download->create($data['download']);

    }

    public function detalhar($id)
    {
        $download = $this->download->where([
            ['id', '=', $id],
        ])->firstOrFail();
        $series = \App\Serie::lists('titulo', 'id')->all();
        $idiomas = \App\Idioma::lists('titulo', 'id')->all();

        return view('cms::download.detalhar', ['download' => $download, 'series' => $series, 'idiomas' => $idiomas]);
    }

    /*public function alterar(Request $request, $id)
    {
        $data = $request->all();
        $data['download'] += ['cmsuser_id' => auth()->guard('cms')->user()->id];//adiciona id do usuario

        //verifica se o index do campo existe no array e caso não exista inserir o campo com valor vazio.
        foreach($this->campos as $campo){
            if(!array_key_exists($campo, $data)){
                if($campo!='imagem'){
                    $data['download'] += [$campo => ''];
                }
            }
        }
        $download = $this->download->where([
            ['id', '=', $id],
        ])->firstOrFail();

        $file = $request->file('file');

        if($file!=null){
            $filename = rand(1000,9999)."-".clean($file->getClientOriginalName());
            $imagemCms = new ImagemCms();
            $success = $imagemCms->alterar($file, $this->pathImagem, $filename, $this->sizesImagem, $this->widthOriginal, $download);
            if($success){
                $data['download']['imagem'] = $filename;
                $download->update($data['download']);
                return $download->imagem;
            }else{
                return "erro";
            }
        }

        //remover imagem
        if($data['removerImagem']){
            $data['download']['imagem'] = '';
            if(file_exists($this->pathImagem."/".$download->imagem)) {
                unlink($this->pathImagem . "/" . $download->imagem);
            }
        }

        $download->update($data['download']);
        return "Gravado com sucesso";
    }*/

    public function alterar(Request $request, $id)
    {
        $data = $request->all();

        //return $data;

        $data['download'] += ['cmsuser_id' => auth()->guard('cms')->user()->id];//adiciona id do usuario

        //verifica se o index do campo existe no array e caso não exista inserir o campo com valor vazio.
        foreach($this->campos as $campo){
            if(!array_key_exists($campo, $data)){
                if($campo!='imagem' && $campo!='arquivo'){
                    $data['download'] += [$campo => ''];
                }
            }
        }
        $download = $this->download->where([
            ['id', '=', $id],
        ])->firstOrFail();


        $file = $request->file('file');
        $arquivo = $request->file('arquivo');

        //remover imagem
        if($data['removerImagem']){
            $data['download']['imagem'] = '';
            if(file_exists($this->pathImagem."/".$download->imagem)) {
                unlink($this->pathImagem . "/" . $download->imagem);
            }
        }


        if($data['removerArquivo']){
            $data['download']['arquivo'] = '';
            if(file_exists($this->pathArquivo."/".$download->arquivo)) {
                unlink($this->pathArquivo . "/" . $download->arquivo);
            }
        }


        $successFile = true;
        if($file!=null){
            $filename = rand(1000,9999)."-".clean($file->getClientOriginalName());
            $imagemCms = new ImagemCms();
            $successFile = $imagemCms->alterar($file, $this->pathImagem, $filename, $this->sizesImagem, $this->widthOriginal, $download);
            if($successFile){
                $data['download']['imagem'] = $filename;
            }
        }

        $successArquivo = true;
        if($arquivo!=null){
            $filenameArquivo = rand(1000,9999)."-".clean($arquivo->getClientOriginalName());
            $successArquivo = $arquivo->move($this->pathArquivo, $filenameArquivo);
            if($successArquivo){
                $data['download']['arquivo'] = $filenameArquivo;
            }
        }

        if($successFile && $successArquivo){

            $download->update($data['download']);
            return $download->imagem;
        }else{
            return "erro";
        }

        //$download->update($data['download']);
        //return "Gravado com sucesso";
    }

    public function excluir($id)
    {
        //Auth::loginUsingId(2);

        $download = $this->download->where([
            ['id', '=', $id],
        ])->firstOrFail();

        //remover imagens        
        if(!empty($download->imagem)){
            //remover imagens
            $imagemCms = new ImagemCms();
            $imagemCms->excluir($this->pathImagem, $this->sizesImagem, $download);
        }


        if(!empty($download->arquivo)) {
            if (file_exists($this->pathArquivo . "/" . $download->arquivo)) {
                unlink($this->pathArquivo . "/" . $download->arquivo);
            }
        }

        $download->delete();

    }

    


}
