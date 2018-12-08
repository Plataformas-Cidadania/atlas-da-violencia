<?php

namespace Cms\Controllers;

use Cms\Models\ImagemCms;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Facades\Image;

class PeriodicidadeController extends Controller
{
    
    

    public function __construct()
    {
        $this->periodicidade = new \App\Periodicidade;
        $this->idiomaPeriodicidade = new \App\IdiomaPeriodicidade;
        $this->campos = [
            'titulo', 'cmsuser_id', 'idioma_sigla',
        ];
        $this->pathImagem = public_path().'/imagens/periodicidades';
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

        $periodicidades = \App\Periodicidade::all();
        $idiomas = \App\Idioma::lists('titulo', 'sigla')->all();

        return view('cms::periodicidade.listar', ['periodicidades' => $periodicidades, 'idiomas' => $idiomas]);
    }

    public function listar(Request $request)
    {

        //Log::info('CAMPOS: '.$request->campos);

        //Auth::loginUsingId(2);

        $campos = explode(", ", $request->campos);

        /*$periodicidades = DB::table('periodicidades')
            ->select($campos)
            ->where([
                [$request->campoPesquisa, 'like', "%$request->dadoPesquisa%"],
            ])
            ->orderBy($request->ordem, $request->sentido)
            ->paginate($request->itensPorPagina);*/

        $periodicidades = DB::table('periodicidades')
        ->select($campos)
        ->join('idiomas_periodicidades', 'idiomas_periodicidades.periodicidade_id', '=', 'periodicidades.id')
        ->where([
            [$request->campoPesquisa, 'like', "%$request->dadoPesquisa%"],
            ['idiomas_periodicidades.idioma_sigla', 'pt_BR'],
        ])
        ->orderBy($request->ordem, $request->sentido)
        ->paginate($request->itensPorPagina);


        return $periodicidades;
    }

    public function inserir(Request $request)
    {

        $data = $request->all();

        $data['periodicidade'] = [];

        $data['periodicidade'] += ['cmsuser_id' => auth()->guard('cms')->user()->id];//adiciona id do usuario
        $data['idioma'] += ['cmsuser_id' => auth()->guard('cms')->user()->id];//adiciona id do usuario

        //verifica se o index do campo existe no array e caso não exista inserir o campo com valor vazio.
        foreach($this->campos as $campo){
            if(!array_key_exists($campo, $data)){
                $data['periodicidade'] += [$campo => ''];
            }
        }

        $file = $request->file('file');

        if($file!=null){
            $filename = rand(1000,9999)."-".clean($file->getClientOriginalName());
            $imagemCms = new ImagemCms();
            $success = $imagemCms->inserir($file, $this->pathImagem, $filename, $this->sizesImagem, $this->widthOriginal);
            
            if($success){
                $data['periodicidade']['imagem'] = $filename;
                $inserir = $this->periodicidade->create($data['periodicidade']);
                $data['idioma']['periodicidade_id'] = $inserir->id;
                $inserir2 = $this->idiomaPeriodicidade->create($data['idioma']);

                return $this->periodicidade->create($data['periodicidade']);
            }else{
                return "erro";
            }
        }

        //return $this->periodicidade->create($data['periodicidade']);

        $inserir = $this->periodicidade->create($data['periodicidade']);
        $data['idioma']['periodicidade_id'] = $inserir->id;
        $inserir2 = $this->idiomaPeriodicidade->create($data['idioma']);
        return $inserir;

    }

    public function detalhar($id)
    {
        $periodicidade = $this->periodicidade->where([
            ['id', '=', $id],
        ])->firstOrFail();
        return view('cms::periodicidade.detalhar', ['periodicidade' => $periodicidade]);
    }

    public function alterar(Request $request, $id)
    {
        $data = $request->all();
        $data['periodicidade'] += ['cmsuser_id' => auth()->guard('cms')->user()->id];//adiciona id do usuario

        //verifica se o index do campo existe no array e caso não exista inserir o campo com valor vazio.
        foreach($this->campos as $campo){
            if(!array_key_exists($campo, $data)){
                if($campo!='imagem'){
                    $data['periodicidade'] += [$campo => ''];
                }
            }
        }
        $periodicidade = $this->periodicidade->where([
            ['id', '=', $id],
        ])->firstOrFail();

        $file = $request->file('file');

        if($file!=null){
            $filename = rand(1000,9999)."-".clean($file->getClientOriginalName());
            $imagemCms = new ImagemCms();
            $success = $imagemCms->alterar($file, $this->pathImagem, $filename, $this->sizesImagem, $this->widthOriginal, $periodicidade);
            if($success){
                $data['periodicidade']['imagem'] = $filename;
                $periodicidade->update($data['periodicidade']);
                return $periodicidade->imagem;
            }else{
                return "erro";
            }
        }

        //remover imagem
        if($data['removerImagem']){
            $data['periodicidade']['imagem'] = '';
            if(file_exists($this->pathImagem."/".$periodicidade->imagem)) {
                unlink($this->pathImagem . "/" . $periodicidade->imagem);
            }
        }

        $periodicidade->update($data['periodicidade']);
        return "Gravado com sucesso";
    }

    public function excluir($id)
    {
        //Auth::loginUsingId(2);

        $periodicidade = $this->periodicidade->where([
            ['id', '=', $id],
        ])->firstOrFail();

        //remover imagens        
        if(!empty($periodicidade->imagem)){
            //remover imagens
            $imagemCms = new ImagemCms();
            $imagemCms->excluir($this->pathImagem, $this->sizesImagem, $periodicidade);
        }
                

        $periodicidade->delete();

    }

    


}
