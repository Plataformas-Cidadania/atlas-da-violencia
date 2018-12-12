<?php

namespace Cms\Controllers;

use Cms\Models\ImagemCms;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Facades\Image;

class PadraoTerritorioController extends Controller
{
    
    

    public function __construct()
    {
        $this->padraoTerritorio = new \App\PadraoTerritorio;
        $this->campos = [
            'territorios', 'option_abrangencia_id', 'cmsuser_id',
        ];
        $this->pathImagem = public_path().'/imagens/padrao-territorios';
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
        $lang =  App::getLocale();
        $padraoTerritorios = \App\PadraoTerritorio::all();

        $optionsAbrangencias = \App\OptionAbrangencia::
        select('idiomas_options_abrangencias.title', 'options_abrangencias.id')
            ->join('idiomas_options_abrangencias', 'idiomas_options_abrangencias.option_abrangencia_id', '=', 'options_abrangencias.id')
            ->where('idiomas_options_abrangencias.idioma_sigla', $lang)
            ->lists('idiomas_options_abrangencias.title', 'options_abrangencias.id');


        return view('cms::padrao_territorio.listar', ['padraoTerritorios' => $padraoTerritorios, 'optionsAbrangencias' => $optionsAbrangencias]);
    }

    public function listar(Request $request)
    {

        //Log::info('CAMPOS: '.$request->campos);

        //Auth::loginUsingId(2);

        $campos = explode(", ", $request->campos);

        $padraoTerritorios = DB::table('padrao_territorios')
            ->select($campos)
            ->where([
                [$request->campoPesquisa, 'like', "%$request->dadoPesquisa%"],
            ])
            ->orderBy($request->ordem, $request->sentido)
            ->paginate($request->itensPorPagina);
        return $padraoTerritorios;
    }

    public function inserir(Request $request)
    {

        $data = $request->all();

        $data['padraoTerritorio'] += ['cmsuser_id' => auth()->guard('cms')->user()->id];//adiciona id do usuario

        //verifica se o index do campo existe no array e caso não exista inserir o campo com valor vazio.
        foreach($this->campos as $campo){
            if(!array_key_exists($campo, $data)){
                $data['padraoTerritorio'] += [$campo => ''];
            }
        }

        $file = $request->file('file');

        if($file!=null){
            $filename = rand(1000,9999)."-".clean($file->getClientOriginalName());
            $imagemCms = new ImagemCms();
            $success = $imagemCms->inserir($file, $this->pathImagem, $filename, $this->sizesImagem, $this->widthOriginal);
            
            if($success){
                $data['padraoTerritorio']['imagem'] = $filename;
                return $this->padraoTerritorio->create($data['padraoTerritorio']);
            }else{
                return "erro";
            }
        }

        return $this->padraoTerritorio->create($data['padraoTerritorio']);

    }

    public function detalhar($id)
    {

        $lang =  App::getLocale();
        $padraoTerritorio = $this->padraoTerritorio->where([
            ['id', '=', $id],
        ])->firstOrFail();
        $optionsAbrangencias = \App\OptionAbrangencia::
        select('idiomas_options_abrangencias.title', 'options_abrangencias.id')
            ->join('idiomas_options_abrangencias', 'idiomas_options_abrangencias.option_abrangencia_id', '=', 'options_abrangencias.id')
            ->where('idiomas_options_abrangencias.idioma_sigla', $lang)
            ->lists('idiomas_options_abrangencias.title', 'options_abrangencias.id');


        return view('cms::padrao_territorio.detalhar', ['padraoTerritorio' => $padraoTerritorio, 'optionsAbrangencias' => $optionsAbrangencias]);
    }

    public function alterar(Request $request, $id)
    {
        $data = $request->all();
        $data['padraoTerritorio'] += ['cmsuser_id' => auth()->guard('cms')->user()->id];//adiciona id do usuario

        //verifica se o index do campo existe no array e caso não exista inserir o campo com valor vazio.
        foreach($this->campos as $campo){
            if(!array_key_exists($campo, $data)){
                if($campo!='imagem'){
                    $data['padraoTerritorio'] += [$campo => ''];
                }
            }
        }
        $padraoTerritorio = $this->padraoTerritorio->where([
            ['id', '=', $id],
        ])->firstOrFail();

        $file = $request->file('file');

        if($file!=null){
            $filename = rand(1000,9999)."-".clean($file->getClientOriginalName());
            $imagemCms = new ImagemCms();
            $success = $imagemCms->alterar($file, $this->pathImagem, $filename, $this->sizesImagem, $this->widthOriginal, $padraoTerritorio);
            if($success){
                $data['padraoTerritorio']['imagem'] = $filename;
                $padraoTerritorio->update($data['padraoTerritorio']);
                return $padraoTerritorio->imagem;
            }else{
                return "erro";
            }
        }

        //remover imagem
        if($data['removerImagem']){
            $data['padraoTerritorio']['imagem'] = '';
            if(file_exists($this->pathImagem."/".$padraoTerritorio->imagem)) {
                unlink($this->pathImagem . "/" . $padraoTerritorio->imagem);
            }
        }

        $padraoTerritorio->update($data['padraoTerritorio']);
        return "Gravado com sucesso";
    }

    public function excluir($id)
    {
        //Auth::loginUsingId(2);

        $padraoTerritorio = $this->padraoTerritorio->where([
            ['id', '=', $id],
        ])->firstOrFail();

        //remover imagens        
        if(!empty($padraoTerritorio->imagem)){
            //remover imagens
            $imagemCms = new ImagemCms();
            $imagemCms->excluir($this->pathImagem, $this->sizesImagem, $padraoTerritorio);
        }
                

        $padraoTerritorio->delete();

    }

    


}
