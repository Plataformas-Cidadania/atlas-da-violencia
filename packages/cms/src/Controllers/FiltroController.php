<?php

namespace Cms\Controllers;

use Cms\Models\ImagemCms;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Facades\Image;

class FiltroController extends Controller
{
    
    

    public function __construct()
    {
        $this->filtro = new \App\Filtro;
        $this->campos = [
            'titulo', 'cmsuser_id',
        ];
        $this->pathImagem = public_path().'/imagens/filtros';
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

        $filtros = \App\Filtro::all();

        return view('cms::filtro.listar', ['filtros' => $filtros]);
    }

    public function listar(Request $request)
    {

        //Log::info('CAMPOS: '.$request->campos);

        //Auth::loginUsingId(2);

        $campos = explode(", ", $request->campos);

        $filtros = DB::table('filtros')
            ->select($campos)
            ->where([
                [$request->campoPesquisa, 'like', "%$request->dadoPesquisa%"],
            ])
            ->orderBy($request->ordem, $request->sentido)
            ->paginate($request->itensPorPagina);
        return $filtros;
    }

    public function inserir(Request $request)
    {

        $data = $request->all();

        $data['filtro'] += ['cmsuser_id' => auth()->guard('cms')->user()->id];//adiciona id do usuario

        //verifica se o index do campo existe no array e caso não exista inserir o campo com valor vazio.
        foreach($this->campos as $campo){
            if(!array_key_exists($campo, $data)){
                $data['filtro'] += [$campo => ''];
            }
        }

        $file = $request->file('file');

        if($file!=null){
            $filename = rand(1000,9999)."-".clean($file->getClientOriginalName());
            $imagemCms = new ImagemCms();
            $success = $imagemCms->inserir($file, $this->pathImagem, $filename, $this->sizesImagem, $this->widthOriginal);
            
            if($success){
                $data['filtro']['imagem'] = $filename;
                return $this->filtro->create($data['filtro']);
            }else{
                return "erro";
            }
        }

        return $this->filtro->create($data['filtro']);

    }

    public function detalhar($id)
    {
        $filtro = $this->filtro->where([
            ['id', '=', $id],
        ])->firstOrFail();
        return view('cms::filtro.detalhar', ['filtro' => $filtro]);
    }

    public function alterar(Request $request, $id)
    {
        $data = $request->all();
        $data['filtro'] += ['cmsuser_id' => auth()->guard('cms')->user()->id];//adiciona id do usuario

        //verifica se o index do campo existe no array e caso não exista inserir o campo com valor vazio.
        foreach($this->campos as $campo){
            if(!array_key_exists($campo, $data)){
                if($campo!='imagem'){
                    $data['filtro'] += [$campo => ''];
                }
            }
        }
        $filtro = $this->filtro->where([
            ['id', '=', $id],
        ])->firstOrFail();

        $file = $request->file('file');

        if($file!=null){
            $filename = rand(1000,9999)."-".clean($file->getClientOriginalName());
            $imagemCms = new ImagemCms();
            $success = $imagemCms->alterar($file, $this->pathImagem, $filename, $this->sizesImagem, $this->widthOriginal, $filtro);
            if($success){
                $data['filtro']['imagem'] = $filename;
                $filtro->update($data['filtro']);
                return $filtro->imagem;
            }else{
                return "erro";
            }
        }

        //remover imagem
        if($data['removerImagem']){
            $data['filtro']['imagem'] = '';
            if(file_exists($this->pathImagem."/".$filtro->imagem)) {
                unlink($this->pathImagem . "/" . $filtro->imagem);
            }
        }

        $filtro->update($data['filtro']);
        return "Gravado com sucesso";
    }

    public function excluir($id)
    {
        //Auth::loginUsingId(2);

        $filtro = $this->filtro->where([
            ['id', '=', $id],
        ])->firstOrFail();

        //remover imagens        
        if(!empty($filtro->imagem)){
            //remover imagens
            $imagemCms = new ImagemCms();
            $imagemCms->excluir($this->pathImagem, $this->sizesImagem, $filtro);
        }
                

        $filtro->delete();

    }

    


}
