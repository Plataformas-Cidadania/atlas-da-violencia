<?php

namespace Cms\Controllers;

use Cms\Models\ImagemCms;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Facades\Image;

class ParceiroController extends Controller
{
    
    

    public function __construct()
    {
        $this->parceiro = new \App\Parceiro;
        $this->campos = [
            'imagem', 'titulo', 'descricao', 'arquivo', 'url', 'posicao', 'idioma_sigla', 'cmsuser_id',
        ];
        $this->pathImagem = public_path().'/imagens/parceiros';
        $this->sizesImagem = [
            'xs' => ['width' => 140, 'height' => 79],
            'sm' => ['width' => 480, 'height' => 270],
            'md' => ['width' => 580, 'height' => 326],
            'lg' => ['width' => 1170, 'height' => 658]
        ];
        $this->widthOriginal = true;

        $this->pathArquivo = public_path().'/arquivos/parceiros';
    }

    function index()
    {

        $parceiros = \App\Parceiro::all();
        //$idiomas = \App\Idioma::lists('titulo', 'id')->all();


        return view('cms::parceiro.listar', ['parceiros' => $parceiros]);
        //return view('cms::parceiro.listar', ['parceiros' => $parceiros, 'idiomas' => $idiomas]);
    }

    public function listar(Request $request)
    {

        //Log::info('CAMPOS: '.$request->campos);

        //Auth::loginUsingId(2);

        $campos = explode(", ", $request->campos);

        $parceiros = DB::table('parceiros')
            ->select($campos)
            ->where([
                [$request->campoPesquisa, 'ilike', "%$request->dadoPesquisa%"],
            ])
            ->orderBy($request->ordem, $request->sentido)
            ->paginate($request->itensPorPagina);
        return $parceiros;
    }


    public function inserir(Request $request)
    {

        $data = $request->all();

        $data['parceiro'] += ['idioma_sigla' => "pt_BR"];
        $data['parceiro'] += ['cmsuser_id' => auth()->guard('cms')->user()->id];//adiciona id do usuario

        //verifica se o index do campo existe no array e caso não exista inserir o campo com valor vazio.
        foreach($this->campos as $campo){
            if(!array_key_exists($campo, $data)){
                $data['parceiro'] += [$campo => ''];
            }
        }

        $file = $request->file('file');
        $arquivo = $request->file('arquivo');
	
	Log::info($data);

        $successFile = true;
        if($file!=null){
            $filename = rand(1000,9999)."-".clean($file->getClientOriginalName());
            $imagemCms = new ImagemCms();
            $successFile = $imagemCms->inserir($file, $this->pathImagem, $filename, $this->sizesImagem, $this->widthOriginal);
            if($successFile){
                $data['parceiro']['imagem'] = $filename;
            }
        }

        $successArquivo = true;
        if($arquivo!=null){
            $filenameArquivo = rand(1000,9999)."-".clean($arquivo->getClientOriginalName());
            $successArquivo = $arquivo->move($this->pathArquivo, $filenameArquivo);
            if($successArquivo){
                $data['parceiro']['arquivo'] = $filenameArquivo;
            }
        }


        if($successFile && $successArquivo){
            return $this->parceiro->create($data['parceiro']);
        }else{
            return "erro";
        }


        return $this->parceiro->create($data['parceiro']);

    }

    public function detalhar($id)
    {
        $parceiro = $this->parceiro->where([
            ['id', '=', $id],
        ])->firstOrFail();
        //$idiomas = \App\Idioma::lists('titulo', 'id')->all();

        return view('cms::parceiro.detalhar', ['parceiro' => $parceiro]);
        //return view('cms::parceiro.detalhar', ['parceiro' => $parceiro, 'idiomas' => $idiomas]);
    }

    /*public function alterar(Request $request, $id)
    {
        $data = $request->all();
        $data['parceiro'] += ['cmsuser_id' => auth()->guard('cms')->user()->id];//adiciona id do usuario

        //verifica se o index do campo existe no array e caso não exista inserir o campo com valor vazio.
        foreach($this->campos as $campo){
            if(!array_key_exists($campo, $data)){
                if($campo!='imagem'){
                    $data['parceiro'] += [$campo => ''];
                }
            }
        }
        $parceiro = $this->parceiro->where([
            ['id', '=', $id],
        ])->firstOrFail();

        $file = $request->file('file');

        if($file!=null){
            $filename = rand(1000,9999)."-".clean($file->getClientOriginalName());
            $imagemCms = new ImagemCms();
            $success = $imagemCms->alterar($file, $this->pathImagem, $filename, $this->sizesImagem, $this->widthOriginal, $parceiro);
            if($success){
                $data['parceiro']['imagem'] = $filename;
                $parceiro->update($data['parceiro']);
                return $parceiro->imagem;
            }else{
                return "erro";
            }
        }

        //remover imagem
        if($data['removerImagem']){
            $data['parceiro']['imagem'] = '';
            if(file_exists($this->pathImagem."/".$parceiro->imagem)) {
                unlink($this->pathImagem . "/" . $parceiro->imagem);
            }
        }

        $parceiro->update($data['parceiro']);
        return "Gravado com sucesso";
    }*/

    public function alterar(Request $request, $id)
    {
        $data = $request->all();

        //return $data;

        $data['parceiro'] += ['cmsuser_id' => auth()->guard('cms')->user()->id];//adiciona id do usuario

        //verifica se o index do campo existe no array e caso não exista inserir o campo com valor vazio.
        foreach($this->campos as $campo){
            if(!array_key_exists($campo, $data)){
                if($campo!='imagem' && $campo!='arquivo'){
                    $data['parceiro'] += [$campo => ''];
                }
            }
        }
        $parceiro = $this->parceiro->where([
            ['id', '=', $id],
        ])->firstOrFail();


        $file = $request->file('file');
        $arquivo = $request->file('arquivo');



        //remover imagem
        if($data['removerImagem']){
            $data['parceiro']['imagem'] = '';
            if(file_exists($this->pathImagem."/".$parceiro->imagem)) {
                unlink($this->pathImagem . "/" . $parceiro->imagem);
            }
        }


        if($data['removerArquivo']){
            $data['parceiro']['arquivo'] = '';
            if(file_exists($this->pathArquivo."/".$parceiro->arquivo)) {
                unlink($this->pathArquivo . "/" . $parceiro->arquivo);
            }
        }


        $successFile = true;
        if($file!=null){
            $filename = rand(1000,9999)."-".clean($file->getClientOriginalName());
            $imagemCms = new ImagemCms();
            $successFile = $imagemCms->alterar($file, $this->pathImagem, $filename, $this->sizesImagem, $this->widthOriginal, $parceiro);
            if($successFile){
                $data['parceiro']['imagem'] = $filename;
            }
        }

        $successArquivo = true;
        if($arquivo!=null){
            $filenameArquivo = rand(1000,9999)."-".clean($arquivo->getClientOriginalName());
            $successArquivo = $arquivo->move($this->pathArquivo, $filenameArquivo);
            if($successArquivo){
                $data['parceiro']['arquivo'] = $filenameArquivo;
            }
        }

        if($successFile && $successArquivo){

            $parceiro->update($data['parceiro']);
            return $parceiro->imagem;
        }else{
            return "erro";
        }

        //$parceiro->update($data['parceiro']);
        //return "Gravado com sucesso";
    }

    public function excluir($id)
    {
        //Auth::loginUsingId(2);

        $parceiro = $this->parceiro->where([
            ['id', '=', $id],
        ])->firstOrFail();

        //remover imagens        
        if(!empty($parceiro->imagem)){
            //remover imagens
            $imagemCms = new ImagemCms();
            $imagemCms->excluir($this->pathImagem, $this->sizesImagem, $parceiro);
        }


        if(!empty($parceiro->arquivo)) {
            if (file_exists($this->pathArquivo . "/" . $parceiro->arquivo)) {
                unlink($this->pathArquivo . "/" . $parceiro->arquivo);
            }
        }

        $parceiro->delete();

    }

    public function status($id)
    {
        $tipo_atual = DB::table('parceiros')->where('id', $id)->first();
        $status = $tipo_atual->status == 0 ? 1 : 0;
        DB::table('parceiros')->where('id', $id)->update(['status' => $status]);

    }

    public function positionUp($id)
    {

        $posicao_atual = DB::table('parceiros')->where('id', $id)->first();
        $upPosicao = $posicao_atual->posicao-1;
        $posicao = $posicao_atual->posicao;

        //Coloca com a posicao do anterior
        DB::table('parceiros')->where('posicao', $upPosicao)->update(['posicao' => $posicao]);

        //atualiza a posicao para o anterior
        DB::table('parceiros')->where('id', $id)->update(['posicao' => $upPosicao]);


    }

    public function positionDown($id)
    {

        $posicao_atual = DB::table('parceiros')->where('id', $id)->first();
        $upPosicao = $posicao_atual->posicao+1;
        $posicao = $posicao_atual->posicao;

        //Coloca com a posicao do anterior
        DB::table('parceiros')->where('posicao', $upPosicao)->update(['posicao' => $posicao]);

        //atualiza a posicao para o anterior
        DB::table('parceiros')->where('id', $id)->update(['posicao' => $upPosicao]);

    }


}
