<?php

namespace Cms\Controllers;

use Cms\Models\ImagemCms;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Facades\Image;

class IndicadorController extends Controller
{
    
    

    public function __construct()
    {
        $this->indicador = new \App\Indicador;
        $this->campos = [
            'imagem', 'titulo', 'sigla', 'cmsuser_id',
        ];
        $this->pathImagem = public_path().'/imagens/indicadores';
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

        $indicadores = \App\Indicador::all();

        return view('cms::indicador.listar', ['indicadores' => $indicadores]);
    }

    public function listar(Request $request)
    {

        //Log::info('CAMPOS: '.$request->campos);

        //Auth::loginUsingId(2);

        $campos = explode(", ", $request->campos);

        $indicadores = DB::table('indicadores')
            ->select($campos)
            ->where([
                [$request->campoPesquisa, 'like', "%$request->dadoPesquisa%"],
            ])
            ->orderBy($request->ordem, $request->sentido)
            ->paginate($request->itensPorPagina);
        return $indicadores;
    }

    public function inserir(Request $request)
    {

        $data = $request->all();

        $data['indicador'] += ['cmsuser_id' => auth()->guard('cms')->user()->id];//adiciona id do usuario

        //verifica se o index do campo existe no array e caso não exista inserir o campo com valor vazio.
        foreach($this->campos as $campo){
            if(!array_key_exists($campo, $data)){
                $data['indicador'] += [$campo => ''];
            }
        }

        $file = $request->file('file');

        if($file!=null){
            $filename = rand(1000,9999)."-".clean($file->getClientOriginalName());
            $imagemCms = new ImagemCms();
            $success = $imagemCms->inserir($file, $this->pathImagem, $filename, $this->sizesImagem, $this->widthOriginal);
            
            if($success){
                $data['indicador']['imagem'] = $filename;
                return $this->indicador->create($data['indicador']);
            }else{
                return "erro";
            }
        }

        return $this->indicador->create($data['indicador']);

    }

    public function detalhar($id)
    {
        $indicador = $this->indicador->where([
            ['id', '=', $id],
        ])->firstOrFail();
        return view('cms::indicador.detalhar', ['indicador' => $indicador]);
    }

    public function alterar(Request $request, $id)
    {
        $data = $request->all();
        $data['indicador'] += ['cmsuser_id' => auth()->guard('cms')->user()->id];//adiciona id do usuario

        //verifica se o index do campo existe no array e caso não exista inserir o campo com valor vazio.
        foreach($this->campos as $campo){
            if(!array_key_exists($campo, $data)){
                if($campo!='imagem'){
                    $data['indicador'] += [$campo => ''];
                }
            }
        }
        $indicador = $this->indicador->where([
            ['id', '=', $id],
        ])->firstOrFail();

        $file = $request->file('file');

        if($file!=null){
            $filename = rand(1000,9999)."-".clean($file->getClientOriginalName());
            $imagemCms = new ImagemCms();
            $success = $imagemCms->alterar($file, $this->pathImagem, $filename, $this->sizesImagem, $this->widthOriginal, $indicador);
            if($success){
                $data['indicador']['imagem'] = $filename;
                $indicador->update($data['indicador']);
                return $indicador->imagem;
            }else{
                return "erro";
            }
        }

        //remover imagem
        if($data['removerImagem']){
            $data['indicador']['imagem'] = '';
            if(file_exists($this->pathImagem."/".$indicador->imagem)) {
                unlink($this->pathImagem . "/" . $indicador->imagem);
            }
        }

        $indicador->update($data['indicador']);
        return "Gravado com sucesso";
    }

    public function excluir($id)
    {
        //Auth::loginUsingId(2);

        $indicador = $this->indicador->where([
            ['id', '=', $id],
        ])->firstOrFail();

        //remover imagens        
        if(!empty($indicador->imagem)){
            //remover imagens
            $imagemCms = new ImagemCms();
            $imagemCms->excluir($this->pathImagem, $this->sizesImagem, $indicador);
        }
                

        $indicador->delete();

    }

    


}
