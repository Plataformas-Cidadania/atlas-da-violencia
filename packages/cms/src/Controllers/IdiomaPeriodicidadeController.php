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

class IdiomaPeriodicidadeController extends Controller
{
    
    

    public function __construct()
    {
        $this->idiomaPeriodicidade = new \App\IdiomaPeriodicidade;
        $this->campos = [
            'titulo', 'idioma_sigla', 'periodicidade_id', 'cmsuser_id',
        ];
        $this->pathImagem = public_path().'/imagens/idiomas_periodicidades';
        $this->sizesImagem = [
            'xs' => ['width' => 140, 'height' => 79],
            'sm' => ['width' => 480, 'height' => 270],
            'md' => ['width' => 580, 'height' => 326],
            'lg' => ['width' => 1170, 'height' => 658]
        ];
        $this->widthOriginal = true;
    }

    function index($periodicidade_id)
    {
        $periodicidade = \App\Periodicidade::where('id', $periodicidade_id)->first();
        $idiomas = \App\Idioma::lists('titulo', 'sigla')->all();

        return view('cms::idiomas_periodicidades.listar', ['periodicidade_id' => $periodicidade->id, 'idiomas' => $idiomas]);
    }

    public function listar(Request $request)
    {

        $campos = explode(", ", $request->campos);

        $periodicidades = DB::table('idiomas_periodicidades')
            ->select($campos)
            ->where([
                [$request->campoPesquisa, 'ilike', "%$request->dadoPesquisa%"],
            ])
            ->where('periodicidade_id', $request->periodicidade_id)
            ->orderBy($request->ordem, $request->sentido)
            ->paginate($request->itensPorPagina);
        return $periodicidades;
    }

    public function inserir(Request $request)
    {

        $data = $request->all();

        $data['periodicidade'] += ['cmsuser_id' => auth()->guard('cms')->user()->id];//adiciona id do usuario

        /*if(empty($data['periodicidade']['periodicidade_id'])){
            $data['periodicidade']['periodicidade_id'] = 0;
        }*/

        //$data['periodicidade']['tipo_valores'] = 0;

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
                return $this->idiomaPeriodicidade->create($data['periodicidade']);
            }else{
                return "erro";
            }
        }

        $periodicidade = $this->idiomaPeriodicidade->create($data['periodicidade']);

        return $periodicidade;

    }

    public function detalhar($id)
    {
        $idioma_periodicidade = $this->idiomaPeriodicidade
            ->where([
            ['id', '=', $id],
        ])->first();
        $idiomas = \App\Idioma::lists('titulo', 'sigla')->all();

        $periodicidade_id = $idioma_periodicidade->periodicidade_id;

        return view('cms::idiomas_periodicidades.detalhar', [
            'idioma_periodicidade' => $idioma_periodicidade,
            'idiomas' => $idiomas,
            'periodicidade_id' => $periodicidade_id
        ]);
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

	    $data['periodicidade']['tipo_valores'] = 0;

        $periodicidade = $this->idiomaPeriodicidade->where([
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

        $periodicidade = $this->idiomaPeriodicidade->where([
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
