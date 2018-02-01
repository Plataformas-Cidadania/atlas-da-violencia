<?php

namespace Cms\Controllers;

use Cms\Models\ImagemCms;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Facades\Image;

class UnidadeController extends Controller
{
    
    

    public function __construct()
    {
        $this->unidade = new \App\Unidade;
        $this->campos = [
            'imagem', 'titulo', 'sigla', 'cmsuser_id',
        ];
        $this->pathImagem = public_path().'/imagens/unidades';
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

        $unidades = \App\Unidade::all();

        return view('cms::unidade.listar', ['unidades' => $unidades]);
    }

    public function listar(Request $request)
    {

        //Log::info('CAMPOS: '.$request->campos);

        //Auth::loginUsingId(2);

        $campos = explode(", ", $request->campos);

        $unidades = DB::table('unidades')
            ->select($campos)
            ->where([
                [$request->campoPesquisa, 'like', "%$request->dadoPesquisa%"],
            ])
            ->orderBy($request->ordem, $request->sentido)
            ->paginate($request->itensPorPagina);
        return $unidades;
    }

    public function inserir(Request $request)
    {

        $data = $request->all();

        $data['unidade'] += ['cmsuser_id' => auth()->guard('cms')->user()->id];//adiciona id do usuario

        //verifica se o index do campo existe no array e caso não exista inserir o campo com valor vazio.
        foreach($this->campos as $campo){
            if(!array_key_exists($campo, $data)){
                $data['unidade'] += [$campo => ''];
            }
        }

        $file = $request->file('file');

        if($file!=null){
            $filename = rand(1000,9999)."-".clean($file->getClientOriginalName());
            $imagemCms = new ImagemCms();
            $success = $imagemCms->inserir($file, $this->pathImagem, $filename, $this->sizesImagem, $this->widthOriginal);
            
            if($success){
                $data['unidade']['imagem'] = $filename;
                return $this->unidade->create($data['unidade']);
            }else{
                return "erro";
            }
        }

        return $this->unidade->create($data['unidade']);

    }

    public function detalhar($id)
    {
        $unidade = $this->unidade->where([
            ['id', '=', $id],
        ])->firstOrFail();
        return view('cms::unidade.detalhar', ['unidade' => $unidade]);
    }

    public function alterar(Request $request, $id)
    {
        $data = $request->all();
        $data['unidade'] += ['cmsuser_id' => auth()->guard('cms')->user()->id];//adiciona id do usuario

        //verifica se o index do campo existe no array e caso não exista inserir o campo com valor vazio.
        foreach($this->campos as $campo){
            if(!array_key_exists($campo, $data)){
                if($campo!='imagem'){
                    $data['unidade'] += [$campo => ''];
                }
            }
        }
        $unidade = $this->unidade->where([
            ['id', '=', $id],
        ])->firstOrFail();

        $file = $request->file('file');

        if($file!=null){
            $filename = rand(1000,9999)."-".clean($file->getClientOriginalName());
            $imagemCms = new ImagemCms();
            $success = $imagemCms->alterar($file, $this->pathImagem, $filename, $this->sizesImagem, $this->widthOriginal, $unidade);
            if($success){
                $data['unidade']['imagem'] = $filename;
                $unidade->update($data['unidade']);
                return $unidade->imagem;
            }else{
                return "erro";
            }
        }

        //remover imagem
        if($data['removerImagem']){
            $data['unidade']['imagem'] = '';
            if(file_exists($this->pathImagem."/".$unidade->imagem)) {
                unlink($this->pathImagem . "/" . $unidade->imagem);
            }
        }

        $unidade->update($data['unidade']);
        return "Gravado com sucesso";
    }

    public function excluir($id)
    {
        //Auth::loginUsingId(2);

        $unidade = $this->unidade->where([
            ['id', '=', $id],
        ])->firstOrFail();

        //remover imagens        
        if(!empty($unidade->imagem)){
            //remover imagens
            $imagemCms = new ImagemCms();
            $imagemCms->excluir($this->pathImagem, $this->sizesImagem, $unidade);
        }
                

        $unidade->delete();

    }

    


}
