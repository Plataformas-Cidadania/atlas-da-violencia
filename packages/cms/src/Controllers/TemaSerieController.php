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

class TemaSerieController extends Controller
{
    
    private $subtemas = [];

    public function __construct()
    {
        $this->temaSerie = new \App\TemaSerie;
        $this->campos = [
            'temas_id', 'serie_id',
        ];
        $this->pathImagem = public_path().'/imagens/temas_series';
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
        $temas = [];
        $temas = $this->mountListSubTemas($temas, 0, "");

        //Log::info($this->subtemas);

        return view('cms::temas_series.listar', ['serie_id' => $serie->id, 'temas' => $this->subtemas]);
    }

    private function mountListSubTemas($arrayTemas, $tema_id, $nivel){
        $temas = \App\Tema::select('id', 'tema')->where('tema_id', $tema_id)->orderBy('id')->get();
        //monta um array apenas com os nós folhas (níveis que não possuem filhos)
        if(count($temas)===0){
            $pai = \App\Tema::find($tema_id);
            $this->subtemas[$pai->id] = $nivel;
        }
        foreach($temas as $index => $tema){
            $titulo = $nivel.' - '.$tema->tema;
            if($nivel==""){
                $titulo = $tema->tema;
            }
            $arrayTemas[$tema->id] = $titulo;
            $arrayTemas = $this->mountListSubTemas($arrayTemas, $tema->id, $titulo);
        }
        return $arrayTemas;
    }

    private function mountListTemas($arrayTemas, $tema_id, $nivel){
        $temas = \App\Tema::select('id', 'tema')->where('tema_id', $tema_id)->orderBy('id')->get();
        //monta um array apenas com os nós folhas (níveis que não possuem filhos)
        if(count($temas)===0){
            array_push($this->subtemas, $nivel);
        }
        foreach($temas as $index => $tema){
            $titulo = $nivel.' - '.$tema->tema;
            if($nivel==""){
                $titulo = $tema->tema;
            }
            $arrayTemas[$tema->id] = $titulo;
            $arrayTemas = $this->mountListTemas($arrayTemas, $tema->id, $titulo);
        }
        return $arrayTemas;
    }

    public function listar(Request $request)
    {

        $campos = explode(", ", $request->campos);

        $series = DB::table('temas_series')
            ->select($campos)
            ->join('series', 'series.id', '=', 'temas_series.serie_id')
            ->join('temas', 'temas.id', '=', 'temas_series.tema_id')
            ->where([
                [$request->campoPesquisa, 'ilike', "%$request->dadoPesquisa%"],
            ])
            ->where('temas_series.serie_id', $request->serie_id)
            ->orderBy($request->ordem, $request->sentido)
            ->paginate($request->itensPorPagina);
        return $series;
    }

    public function inserir(Request $request)
    {

        $data = $request->all();

        $data['tema'] += ['cmsuser_id' => auth()->guard('cms')->user()->id];//adiciona id do usuario

        /*if(empty($data['tema']['serie_id'])){
            $data['tema']['serie_id'] = 0;
        }*/

        //$data['tema']['tipo_valores'] = 0;

        //verifica se o index do campo existe no array e caso não exista inserir o campo com valor vazio.
        foreach($this->campos as $campo){
            if(!array_key_exists($campo, $data)){
                $data['tema'] += [$campo => ''];
            }
        }

        $file = $request->file('file');

        if($file!=null){
            $filename = rand(1000,9999)."-".clean($file->getClientOriginalName());
            $imagemCms = new ImagemCms();
            $success = $imagemCms->inserir($file, $this->pathImagem, $filename, $this->sizesImagem, $this->widthOriginal);
            
            if($success){
                $data['tema']['imagem'] = $filename;
                return $this->temaSerie->create($data['tema']);
            }else{
                return "erro";
            }
        }

        $serie = $this->temaSerie->create($data['tema']);

        return $serie;

    }

    public function detalhar($id)
    {
        $tema_serie = $this->temaSerie
            ->where([
            ['id', '=', $id],
        ])->first();
        $temas = [];
        $temas = $this->mountListTemas($temas, 0, "");

        $serie_id = $tema_serie->serie_id;

        return view('cms::temas_series.detalhar', [
            'tema_serie' => $tema_serie,
            'temas' => $temas,
            'serie_id' => $serie_id
        ]);
    }

    public function alterar(Request $request, $id)
    {
        $data = $request->all();
        $data['tema'] += ['cmsuser_id' => auth()->guard('cms')->user()->id];//adiciona id do usuario

        //verifica se o index do campo existe no array e caso não exista inserir o campo com valor vazio.
        foreach($this->campos as $campo){
            if(!array_key_exists($campo, $data)){
                if($campo!='imagem'){
                    $data['tema'] += [$campo => ''];
                }
            }
        }

	    $data['tema']['tipo_valores'] = 0;

        $serie = $this->temaSerie->where([
            ['id', '=', $id],
        ])->firstOrFail();

        $file = $request->file('file');

        if($file!=null){
            $filename = rand(1000,9999)."-".clean($file->getClientOriginalName());
            $imagemCms = new ImagemCms();
            $success = $imagemCms->alterar($file, $this->pathImagem, $filename, $this->sizesImagem, $this->widthOriginal, $serie);
            if($success){
                $data['tema']['imagem'] = $filename;
                $serie->update($data['tema']);
                return $serie->imagem;
            }else{
                return "erro";
            }
        }

        //remover imagem
        if($data['removerImagem']){
            $data['tema']['imagem'] = '';
            if(file_exists($this->pathImagem."/".$serie->imagem)) {
                unlink($this->pathImagem . "/" . $serie->imagem);
            }
        }

        $serie->update($data['tema']);
        return "Gravado com sucesso";
    }

    public function excluir($id)
    {
        //Auth::loginUsingId(2);

        $serie = $this->temaSerie->where([
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
