<?php

namespace Cms\Controllers;

use Cms\Models\ImagemCms;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Facades\Image;

class WebindicadorController extends Controller
{
    
    

    public function __construct()
    {
        $this->webindicador = new \App\Webindicador;
        $this->campos = [
            'imagem', 'titulo', 'url', 'idioma_sigla', 'arquivo', 'posicao', 'cmsuser_id',
        ];
        $this->pathImagem = public_path().'/imagens/webindicadores';
        $this->sizesImagem = [
            'xs' => ['width' => 34, 'height' => 23],
            'sm' => ['width' => 50, 'height' => 34],
            'md' => ['width' => 100, 'height' => 68],
            'lg' => ['width' => 150, 'height' => 101]
        ];
        $this->widthOriginal = true;
	    $this->pathArquivo = public_path().'/arquivos/rmd';
    }

    function index()
    {

        $webindicadores = \App\Webindicador::all();
        $idiomas = \App\Idioma::lists('titulo', 'sigla')->all();

        return view('cms::webindicador.listar', ['webindicadores' => $webindicadores, 'idiomas' => $idiomas ]);
    }

    public function listar(Request $request)
    {

        //Log::info('CAMPOS: '.$request->campos);

        //Auth::loginUsingId(2);

        $campos = explode(", ", $request->campos);

        $webindicadores = DB::table('webindicadores')
            ->select($campos)
            ->where([
                [$request->campoPesquisa, 'like', "%$request->dadoPesquisa%"],
            ])
            ->orderBy($request->ordem, $request->sentido)
            ->paginate($request->itensPorPagina);
        return $webindicadores;
    }

    public function inserir(Request $request)
    {

        $data = $request->all();

        $data['webindicador'] += ['cmsuser_id' => auth()->guard('cms')->user()->id];//adiciona id do usuario

        //verifica se o index do campo existe no array e caso não exista inserir o campo com valor vazio.
        foreach($this->campos as $campo){
            if(!array_key_exists($campo, $data)){
                $data['webindicador'] += [$campo => ''];
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
                $data['webindicador']['imagem'] = $filename;
            }
        }

        $successArquivo = true;
        if($arquivo!=null){
            $filenameArquivo = rand(1000,9999)."-".clean($arquivo->getClientOriginalName());
            $successArquivo = $arquivo->move($this->pathArquivo, $filenameArquivo);
            if($successArquivo){
                $data['webindicador']['arquivo'] = $filenameArquivo;
            }
        }


        if($successFile && $successArquivo){
            return $this->webindicador->create($data['webindicador']);
        }else{
            return "erro";
        }

    }

    public function detalhar($id)
    {
        $webindicador = $this->webindicador->where([
            ['id', '=', $id],
        ])->firstOrFail();
        $idiomas = \App\Idioma::lists('titulo', 'sigla')->all();
        return view('cms::webindicador.detalhar', ['webindicador' => $webindicador, 'idiomas' => $idiomas]);
    }

    public function alterar(Request $request, $id)
    {
        $data = $request->all();
        $data['webindicador'] += ['cmsuser_id' => auth()->guard('cms')->user()->id];//adiciona id do usuario

        //verifica se o index do campo existe no array e caso não exista inserir o campo com valor vazio.
        foreach($this->campos as $campo){
            if(!array_key_exists($campo, $data)){
                if($campo!='imagem' && $campo!='arquivo'){
                    $data['webindicador'] += [$campo => ''];
                }
            }
        }
        $webindicador = $this->webindicador->where([
            ['id', '=', $id],
        ])->firstOrFail();

        $file = $request->file('file');
	    $arquivo = $request->file('arquivo');

        //remover imagem
        if($data['removerImagem']){
            $data['webindicador']['imagem'] = '';
            if(file_exists($this->pathImagem."/".$webindicador->imagem)) {
                unlink($this->pathImagem . "/" . $webindicador->imagem);
            }
        }

        if($data['removerArquivo']){
            $data['webindicador']['arquivo'] = '';
            if(file_exists($this->pathArquivo."/".$webindicador->arquivo)) {
                unlink($this->pathArquivo . "/" . $webindicador->arquivo);
            }
        }

	$successFile = true;
        if($file!=null){
            $filename = rand(1000,9999)."-".clean($file->getClientOriginalName());
            $imagemCms = new ImagemCms();
            $successFile = $imagemCms->alterar($file, $this->pathImagem, $filename, $this->sizesImagem, $this->widthOriginal, $webindicador);
            if($successFile){
                $data['webindicador']['imagem'] = $filename;
            }
        }
	$successArquivo = true;
        if($arquivo!=null){
            $filenameArquivo = rand(1000,9999)."-".clean($arquivo->getClientOriginalName());
            $successArquivo = $arquivo->move($this->pathArquivo, $filenameArquivo);
            if($successArquivo){
                $data['webindicador']['arquivo'] = $filenameArquivo;
            }
        }

        if($successFile && $successArquivo){

            $webindicador->update($data['webindicador']);
            return $webindicador->imagem;
        }else{
            return "erro";
        }
///

        if($successFile && $successArquivo){

            $download->update($data['webindicador']);
            return $download->imagem;
        }else{
            return "erro";
        }

        /*$webindicador->update($data['webindicador']);
        return "Gravado com sucesso";*/
    }

    public function excluir($id)
    {
        //Auth::loginUsingId(2);

        $webindicador = $this->webindicador->where([
            ['id', '=', $id],
        ])->firstOrFail();

        //remover imagens        
        if(!empty($webindicador->imagem)){
            //remover imagens
            $imagemCms = new ImagemCms();
            $imagemCms->excluir($this->pathImagem, $this->sizesImagem, $webindicador);
        }
        if(!empty($webindicador->arquivo)) {
            if (file_exists($this->pathArquivo . "/" . $webindicador->arquivo)) {
                unlink($this->pathArquivo . "/" . $webindicador->arquivo);
            }
        }

        $webindicador->delete();

    }
    public function positionUp($id)
    {

        $posicao_atual = DB::table('webindicadores')->where('id', $id)->first();
        $upPosicao = $posicao_atual->posicao-1;
        $posicao = $posicao_atual->posicao;

        //Coloca com a posicao do anterior
        DB::table('webindicadores')->where('posicao', $upPosicao)->update(['posicao' => $posicao]);

        //atualiza a posicao para o anterior
        DB::table('webindicadores')->where('id', $id)->update(['posicao' => $upPosicao]);


    }

    public function positionDown($id)
    {

        $posicao_atual = DB::table('webindicadores')->where('id', $id)->first();
        $upPosicao = $posicao_atual->posicao+1;
        $posicao = $posicao_atual->posicao;

        //Coloca com a posicao do anterior
        DB::table('webindicadores')->where('posicao', $upPosicao)->update(['posicao' => $posicao]);

        //atualiza a posicao para o anterior
        DB::table('webindicadores')->where('id', $id)->update(['posicao' => $upPosicao]);

    }

    public function status($id)
    {
        $tipo_atual = DB::table('webindicadores')->where('id', $id)->first();
        $status = $tipo_atual->status == 0 ? 1 : 0;
        DB::table('webindicadores')->where('id', $id)->update(['status' => $status]);
        return $status;
    }

}
