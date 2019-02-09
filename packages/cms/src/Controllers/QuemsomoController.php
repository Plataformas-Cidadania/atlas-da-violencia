<?php

namespace Cms\Controllers;

use Cms\Models\ImagemCms;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Facades\Image;

class QuemsomoController extends Controller
{
    
    

    public function __construct()
    {
        $this->quemsomo = new \App\Quemsomo;
        $this->campos = [
            'imagem', 'origem_id', 'titulo', 'descricao', 'tipo', 'url', 'cmsuser_id', 'idioma_sigla', 'posicao',
        ];
        $this->pathImagem = public_path().'/imagens/quemsomos';
        $this->sizesImagem = [
            'xs' => ['width' => 140, 'height' => 79],
            'sm' => ['width' => 480, 'height' => 270],
            'md' => ['width' => 580, 'height' => 326],
            'lg' => ['width' => 1170, 'height' => 658]
        ];
        $this->widthOriginal = true;
    }

    function index($tipo_id)
    {

        $quemsomos = \App\Quemsomo::all();
        $idiomas = \App\Idioma::lists('titulo', 'sigla')->all();

        return view('cms::quemsomo.listar', ['quemsomos' => $quemsomos, 'idiomas' => $idiomas, 'tipo_id' => $tipo_id]);
    }

    public function listar(Request $request)
    {

        $tipo_id = $request->tipo_id;

        Log::info($tipo_id);

        //Auth::loginUsingId(2);

        $campos = explode(", ", $request->campos);

        $quemsomos = DB::table('quemsomos')
            ->select($campos)
            ->where([
                [$request->campoPesquisa, 'like', "%$request->dadoPesquisa%"],
                ['tipo', $tipo_id],
            ])
            ->orderBy($request->ordem, $request->sentido)
            ->paginate($request->itensPorPagina);
        return $quemsomos;
    }

    public function inserir(Request $request)
    {

        $data = $request->all();

        $data['quemsomo'] += ['cmsuser_id' => auth()->guard('cms')->user()->id];//adiciona id do usuario

        //verifica se o index do campo existe no array e caso não exista inserir o campo com valor vazio.
        foreach($this->campos as $campo){
            if(!array_key_exists($campo, $data)){
                $data['quemsomo'] += [$campo => ''];
            }
        }

        $file = $request->file('file');

        if($file!=null){
            $filename = rand(1000,9999)."-".clean($file->getClientOriginalName());
            $imagemCms = new ImagemCms();
            $success = $imagemCms->inserir($file, $this->pathImagem, $filename, $this->sizesImagem, $this->widthOriginal);
            
            if($success){
                $data['quemsomo']['imagem'] = $filename;
                return $this->quemsomo->create($data['quemsomo']);
            }else{
                return "erro";
            }
        }

        return $this->quemsomo->create($data['quemsomo']);

    }

    public function detalhar($id)
    {
        $quemsomo = $this->quemsomo->where([
            ['id', '=', $id],
        ])->firstOrFail();

        $idiomas = \App\Idioma::lists('titulo', 'sigla')->all();

        return view('cms::quemsomo.detalhar', ['quemsomo' => $quemsomo, 'idiomas' => $idiomas]);
    }

    public function alterar(Request $request, $id)
    {
        $data = $request->all();
        $data['quemsomo'] += ['cmsuser_id' => auth()->guard('cms')->user()->id];//adiciona id do usuario

        //verifica se o index do campo existe no array e caso não exista inserir o campo com valor vazio.
        foreach($this->campos as $campo){
            if(!array_key_exists($campo, $data)){
                if($campo!='imagem'){
                    $data['quemsomo'] += [$campo => ''];
                }
            }
        }
        $quemsomo = $this->quemsomo->where([
            ['id', '=', $id],
        ])->firstOrFail();

        $file = $request->file('file');

        if($file!=null){
            $filename = rand(1000,9999)."-".clean($file->getClientOriginalName());
            $imagemCms = new ImagemCms();
            $success = $imagemCms->alterar($file, $this->pathImagem, $filename, $this->sizesImagem, $this->widthOriginal, $quemsomo);
            if($success){
                $data['quemsomo']['imagem'] = $filename;
                $quemsomo->update($data['quemsomo']);
                return $quemsomo->imagem;
            }else{
                return "erro";
            }
        }

        //remover imagem
        if($data['removerImagem']){
            $data['quemsomo']['imagem'] = '';
            if(file_exists($this->pathImagem."/".$quemsomo->imagem)) {
                unlink($this->pathImagem . "/" . $quemsomo->imagem);
            }
        }

        $quemsomo->update($data['quemsomo']);
        return "Gravado com sucesso";
    }

    public function excluir($id)
    {
        //Auth::loginUsingId(2);

        $quemsomo = $this->quemsomo->where([
            ['id', '=', $id],
        ])->firstOrFail();

        //remover imagens        
        if(!empty($quemsomo->imagem)){
            //remover imagens
            $imagemCms = new ImagemCms();
            $imagemCms->excluir($this->pathImagem, $this->sizesImagem, $quemsomo);
        }
                

        $quemsomo->delete();

    }

    


}
