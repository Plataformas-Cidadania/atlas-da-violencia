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
            'imagem', 'titulo', 'url', 'idioma_sigla', 'cmsuser_id',
        ];
        $this->pathImagem = public_path().'/imagens/webindicadores';
        $this->sizesImagem = [
            'xs' => ['width' => 34, 'height' => 23],
            'sm' => ['width' => 50, 'height' => 34],
            'md' => ['width' => 100, 'height' => 68],
            'lg' => ['width' => 150, 'height' => 101]
        ];
        $this->widthOriginal = true;
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

        if($file!=null){
            $filename = rand(1000,9999)."-".clean($file->getClientOriginalName());
            $imagemCms = new ImagemCms();
            $success = $imagemCms->inserir($file, $this->pathImagem, $filename, $this->sizesImagem, $this->widthOriginal);
            
            if($success){
                $data['webindicador']['imagem'] = $filename;
                return $this->webindicador->create($data['webindicador']);
            }else{
                return "erro";
            }
        }

        return $this->webindicador->create($data['webindicador']);

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
                if($campo!='imagem'){
                    $data['webindicador'] += [$campo => ''];
                }
            }
        }
        $webindicador = $this->webindicador->where([
            ['id', '=', $id],
        ])->firstOrFail();

        $file = $request->file('file');

        if($file!=null){
            $filename = rand(1000,9999)."-".clean($file->getClientOriginalName());
            $imagemCms = new ImagemCms();
            $success = $imagemCms->alterar($file, $this->pathImagem, $filename, $this->sizesImagem, $this->widthOriginal, $webindicador);
            if($success){
                $data['webindicador']['imagem'] = $filename;
                $webindicador->update($data['webindicador']);
                return $webindicador->imagem;
            }else{
                return "erro";
            }
        }

        //remover imagem
        if($data['removerImagem']){
            $data['webindicador']['imagem'] = '';
            if(file_exists($this->pathImagem."/".$webindicador->imagem)) {
                unlink($this->pathImagem . "/" . $webindicador->imagem);
            }
        }

        $webindicador->update($data['webindicador']);
        return "Gravado com sucesso";
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
                

        $webindicador->delete();

    }

    


}
