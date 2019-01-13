<?php

namespace Cms\Controllers;

use Cms\Models\ImagemCms;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Facades\Image;

class OptionAbrangenciaController extends Controller
{
    
    

    public function __construct()
    {
        $this->optionAbrangencia = new \App\OptionAbrangencia;
        $this->idiomaOptionAbrangencia = new \App\IdiomaOptionAbrangencia;
        $this->campos = [
            'id', 'on', 'listAll', 'height', 'cmsuser_id',
        ];
        $this->pathImagem = public_path().'/imagens/options-abrangencias';
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

        $optionAbrangencias = \App\OptionAbrangencia::all();
        $idiomas = \App\Idioma::lists('titulo', 'sigla')->all();

        return view('cms::option_abrangencia.listar', ['optionAbrangencias' => $optionAbrangencias, 'idiomas' => $idiomas]);
    }

    public function listar(Request $request)
    {

        //Log::info('CAMPOS: '.$request->campos);

        //Auth::loginUsingId(2);

        $campos = explode(", ", $request->campos);

        /*$optionAbrangencias = DB::table('optionAbrangencias')
            ->select($campos)
            ->where([
                [$request->campoPesquisa, 'like', "%$request->dadoPesquisa%"],
            ])
            ->orderBy($request->ordem, $request->sentido)
            ->paginate($request->itensPorPagina);*/

        $optionAbrangencias = DB::table('options_abrangencias')
        ->select($campos)
        ->join('idiomas_options_abrangencias', 'idiomas_options_abrangencias.option_abrangencia_id', '=', 'options_abrangencias.id')
        ->where([
            [$request->campoPesquisa, 'like', "%$request->dadoPesquisa%"],
            ['idiomas_options_abrangencias.idioma_sigla', 'pt_BR'],
        ])
        ->orderBy($request->ordem, $request->sentido)
        ->paginate($request->itensPorPagina);


        return $optionAbrangencias;
    }

    public function inserir(Request $request)
    {

        $data = $request->all();

        $data['indicador'] = [];

        $data['optionAbrangencia'] += ['cmsuser_id' => auth()->guard('cms')->user()->id];//adiciona id do usuario


        $data['idioma'] += ['cmsuser_id' => auth()->guard('cms')->user()->id];//adiciona id do usuario
        $data['idioma'] += ['idioma_sigla' => 'pt_BR'];

        //verifica se o index do campo existe no array e caso não exista inserir o campo com valor vazio.
        foreach($this->campos as $campo){
            if(!array_key_exists($campo, $data)){
                $data['optionAbrangencia'] += [$campo => ''];
            }
        }

        $file = $request->file('file');

        if($file!=null){
            $filename = rand(1000,9999)."-".clean($file->getClientOriginalName());
            $imagemCms = new ImagemCms();
            $success = $imagemCms->inserir($file, $this->pathImagem, $filename, $this->sizesImagem, $this->widthOriginal);
            
            if($success){
                $data['optionAbrangencia']['imagem'] = $filename;
                $inserir = $this->optionAbrangencia->create($data['optionAbrangencia']);
                $data['idioma']['option_abrangencia_id'] = $inserir->id;
                $inserir2 = $this->idiomaOptionAbrangencia->create($data['idioma']);

                return $inserir;
            }else{
                return "erro";
            }
        }

        //return $this->optionAbrangencia->create($data['optionAbrangencia']);

        $inserir = $this->optionAbrangencia->create($data['optionAbrangencia']);
        $data['idioma']['option_abrangencia_id'] = $inserir->id;
        $inserir2 = $this->idiomaOptionAbrangencia->create($data['idioma']);
        return $inserir;

    }

    public function detalhar($id)
    {
        $optionAbrangencia = $this->optionAbrangencia->where([
            ['id', '=', $id],
        ])->firstOrFail();
        return view('cms::option_abrangencia.detalhar', ['optionAbrangencia' => $optionAbrangencia]);
    }

    public function alterar(Request $request, $id)
    {
        $data = $request->all();
        $data['optionAbrangencia'] += ['cmsuser_id' => auth()->guard('cms')->user()->id];//adiciona id do usuario

        //verifica se o index do campo existe no array e caso não exista inserir o campo com valor vazio.
        foreach($this->campos as $campo){
            if(!array_key_exists($campo, $data)){
                if($campo!='imagem'){
                    $data['optionAbrangencia'] += [$campo => ''];
                }
            }
        }
        $optionAbrangencia = $this->optionAbrangencia->where([
            ['id', '=', $id],
        ])->firstOrFail();

        $file = $request->file('file');

        if($file!=null){
            $filename = rand(1000,9999)."-".clean($file->getClientOriginalName());
            $imagemCms = new ImagemCms();
            $success = $imagemCms->alterar($file, $this->pathImagem, $filename, $this->sizesImagem, $this->widthOriginal, $optionAbrangencia);
            if($success){
                $data['optionAbrangencia']['imagem'] = $filename;
                $optionAbrangencia->update($data['optionAbrangencia']);
                return $optionAbrangencia->imagem;
            }else{
                return "erro";
            }
        }

        //remover imagem
        if($data['removerImagem']){
            $data['optionAbrangencia']['imagem'] = '';
            if(file_exists($this->pathImagem."/".$optionAbrangencia->imagem)) {
                unlink($this->pathImagem . "/" . $optionAbrangencia->imagem);
            }
        }

        $optionAbrangencia->update($data['optionAbrangencia']);
        return "Gravado com sucesso";
    }

    public function excluir($id)
    {
        //Auth::loginUsingId(2);

        $optionAbrangencia = $this->optionAbrangencia->where([
            ['id', '=', $id],
        ])->firstOrFail();

        //remover imagens        
        if(!empty($optionAbrangencia->imagem)){
            //remover imagens
            $imagemCms = new ImagemCms();
            $imagemCms->excluir($this->pathImagem, $this->sizesImagem, $optionAbrangencia);
        }
                

        $optionAbrangencia->delete();

    }

    


}
