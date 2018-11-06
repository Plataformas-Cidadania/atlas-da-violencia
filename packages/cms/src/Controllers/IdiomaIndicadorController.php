<?php

namespace Cms\Controllers;

use Cms\Models\ImagemCms;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Facades\Image;
use Maatwebsite\Excel\Facades\Excel;

class IdiomaIndicadorController extends Controller
{
    
    

    public function __construct()
    {
        $this->idiomaIndicador = new \App\IdiomaIndicador;
        $this->campos = [
            'imagem', 'titulo', 'idioma_sigla',
        ];
        $this->pathImagem = public_path().'/imagens/idiomas_indicadores';
        $this->sizesImagem = [
            'xs' => ['width' => 140, 'height' => 79],
            'sm' => ['width' => 480, 'height' => 270],
            'md' => ['width' => 580, 'height' => 326],
            'lg' => ['width' => 1170, 'height' => 658]
        ];
        $this->widthOriginal = true;
    }

    function index($indicador_id)
    {
        $indicador = \App\Indicador::where('id', $indicador_id)->first();
        $idiomas = \App\Idioma::lists('titulo', 'sigla')->all();

        return view('cms::idiomas_indicadores.listar', ['indicador_id' => $indicador->id, 'idiomas' => $idiomas]);
    }

    public function listar(Request $request)
    {

        $campos = explode(", ", $request->campos);

        $indicadores = DB::table('idiomas_indicadores')
            ->select($campos)
            ->where([
                [$request->campoPesquisa, 'ilike', "%$request->dadoPesquisa%"],
            ])
            ->where('indicador_id', $request->indicador_id)
            ->orderBy($request->ordem, $request->sentido)
            ->paginate($request->itensPorPagina);
        return $indicadores;
    }

    public function inserir(Request $request)
    {

        $data = $request->all();

        $data['indicador'] += ['cmsuser_id' => auth()->guard('cms')->user()->id];//adiciona id do usuario

        /*if(empty($data['indicador']['indicador_id'])){
            $data['indicador']['indicador_id'] = 0;
        }*/

        //$data['indicador']['tipo_valores'] = 0;

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
                return $this->idiomaIndicador->create($data['indicador']);
            }else{
                return "erro";
            }
        }

        $indicador = $this->idiomaIndicador->create($data['indicador']);

        return $indicador;

    }

    public function detalhar($id)
    {
        $idioma_indicador = $this->idiomaIndicador
            ->where([
            ['id', '=', $id],
        ])->first();
        $idiomas = \App\Idioma::lists('titulo', 'sigla')->all();

        $indicador_id = $idioma_indicador->indicador_id;

        return view('cms::idiomas_indicadores.detalhar', [
            'idioma_indicador' => $idioma_indicador,
            'idiomas' => $idiomas,
            'indicador_id' => $indicador_id
        ]);
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

	    $data['indicador']['tipo_valores'] = 0;

        $indicador = $this->idiomaIndicador->where([
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

        $indicador = $this->idiomaIndicador->where([
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
