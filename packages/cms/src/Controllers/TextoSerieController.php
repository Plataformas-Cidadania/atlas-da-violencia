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

class TextoSerieController extends Controller
{
    
    

    public function __construct()
    {
        $this->textoSerie = new \App\TextoSerie;
        $this->campos = [
            'imagem', 'titulo', 'descricao', 'idioma_sigla',
        ];
        $this->pathImagem = public_path().'/imagens/textos_series';
        $this->sizesImagem = [
            'xs' => ['width' => 140, 'height' => 79],
            'sm' => ['width' => 480, 'height' => 270],
            'md' => ['width' => 580, 'height' => 326],
            'lg' => ['width' => 1170, 'height' => 658]
        ];
        $this->widthOriginal = true;
    }

    function index($serie_id)
    {
        $serie = \App\Serie::where('id', $serie_id)->first();
        $idiomas = \App\Idioma::lists('titulo', 'sigla')->all();

        return view('cms::textos_series.listar', ['serie_id' => $serie->id, 'idiomas' => $idiomas]);
    }

    public function listar(Request $request)
    {

        $campos = explode(", ", $request->campos);

        $series = DB::table('textos_series')
            ->select($campos)
            ->where([
                [$request->campoPesquisa, 'ilike', "%$request->dadoPesquisa%"],
            ])
            ->where('serie_id', $request->serie_id)
            ->orderBy($request->ordem, $request->sentido)
            ->paginate($request->itensPorPagina);
        return $series;
    }

    public function inserir(Request $request)
    {

        $data = $request->all();

        $data['serie'] += ['cmsuser_id' => auth()->guard('cms')->user()->id];//adiciona id do usuario

        /*if(empty($data['serie']['serie_id'])){
            $data['serie']['serie_id'] = 0;
        }*/

        //$data['serie']['tipo_valores'] = 0;

        //verifica se o index do campo existe no array e caso não exista inserir o campo com valor vazio.
        foreach($this->campos as $campo){
            if(!array_key_exists($campo, $data)){
                $data['serie'] += [$campo => ''];
            }
        }

        $file = $request->file('file');

        if($file!=null){
            $filename = rand(1000,9999)."-".clean($file->getClientOriginalName());
            $imagemCms = new ImagemCms();
            $success = $imagemCms->inserir($file, $this->pathImagem, $filename, $this->sizesImagem, $this->widthOriginal);
            
            if($success){
                $data['serie']['imagem'] = $filename;
                return $this->textoSerie->create($data['serie']);
            }else{
                return "erro";
            }
        }

        $serie = $this->textoSerie->create($data['serie']);

        return $serie;

    }

    public function detalhar($id)
    {
        $texto_serie = $this->textoSerie
            ->where([
            ['id', '=', $id],
        ])->first();
        $idiomas = \App\Idioma::lists('titulo', 'sigla')->all();

        $serie_id = $texto_serie->serie_id;

        return view('cms::textos_series.detalhar', [
            'texto_serie' => $texto_serie,
            'idiomas' => $idiomas,
            'serie_id' => $serie_id
        ]);
    }

    public function alterar(Request $request, $id)
    {
        $data = $request->all();
        $data['serie'] += ['cmsuser_id' => auth()->guard('cms')->user()->id];//adiciona id do usuario

        //verifica se o index do campo existe no array e caso não exista inserir o campo com valor vazio.
        foreach($this->campos as $campo){
            if(!array_key_exists($campo, $data)){
                if($campo!='imagem'){
                    $data['serie'] += [$campo => ''];
                }
            }
        }

	    $data['serie']['tipo_valores'] = 0;

        $serie = $this->textoSerie->where([
            ['id', '=', $id],
        ])->firstOrFail();

        $file = $request->file('file');

        if($file!=null){
            $filename = rand(1000,9999)."-".clean($file->getClientOriginalName());
            $imagemCms = new ImagemCms();
            $success = $imagemCms->alterar($file, $this->pathImagem, $filename, $this->sizesImagem, $this->widthOriginal, $serie);
            if($success){
                $data['serie']['imagem'] = $filename;
                $serie->update($data['serie']);
                return $serie->imagem;
            }else{
                return "erro";
            }
        }

        //remover imagem
        if($data['removerImagem']){
            $data['serie']['imagem'] = '';
            if(file_exists($this->pathImagem."/".$serie->imagem)) {
                unlink($this->pathImagem . "/" . $serie->imagem);
            }
        }

        $serie->update($data['serie']);
        return "Gravado com sucesso";
    }

    public function excluir($id)
    {
        //Auth::loginUsingId(2);

        $serie = $this->textoSerie->where([
            ['id', '=', $id],
        ])->firstOrFail();

        //remover imagens        
        if(!empty($serie->imagem)){
            //remover imagens
            $imagemCms = new ImagemCms();
            $imagemCms->excluir($this->pathImagem, $this->sizesImagem, $serie);
        }
                

        $serie->delete();

    }

    
    


}
