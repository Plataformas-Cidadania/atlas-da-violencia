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

class SerieController extends Controller
{
    
    

    public function __construct()
    {
        $this->serie = new \App\Serie;
        $this->campos = [
            'imagem', 'titulo', 'descricao', 'autor', 'fonte', 'link_font', 'cmsuser_id', 'serie_id',
        ];
        $this->pathImagem = public_path().'/imagens/series';
        $this->sizesImagem = [
            'xs' => ['width' => 140, 'height' => 79],
            'sm' => ['width' => 480, 'height' => 270],
            'md' => ['width' => 580, 'height' => 326],
            'lg' => ['width' => 1170, 'height' => 658]
        ];
        $this->widthOriginal = true;
    }

    function index()
    {

        $series = \App\Serie::all();
        //$temas = \App\Tema::lists('titulo', 'id')->all();
        $fontes = \App\Fonte::lists('titulo', 'id')->all();
        $series_relacionado = \App\Serie::lists('titulo', 'id')->all();


        return view('cms::serie.listar', ['series' => $series, 'series_relacionado' => $series_relacionado, 'fontes' => $fontes]);
    }

    public function listar(Request $request)
    {

        //Log::info('CAMPOS: '.$request->campos);

        //Auth::loginUsingId(2);

        $campos = explode(", ", $request->campos);

        $series = DB::table('series')
            ->select($campos)
            ->where([
                [$request->campoPesquisa, 'like', "%$request->dadoPesquisa%"],
            ])
            ->orderBy($request->ordem, $request->sentido)
            ->paginate($request->itensPorPagina);
        return $series;
    }

    public function inserir(Request $request)
    {

        $data = $request->all();

        $data['serie'] += ['cmsuser_id' => auth()->guard('cms')->user()->id];//adiciona id do usuario

        if(empty($data['serie']['serie_id'])){
            $data['serie']['serie_id'] = 0;
        }

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
                return $this->serie->create($data['serie']);
            }else{
                return "erro";
            }
        }

        return $this->serie->create($data['serie']);

    }

    public function detalhar($id)
    {
        $serie = $this->serie->where([
            ['id', '=', $id],
        ])->firstOrFail();


        $fontes = \App\Fonte::lists('titulo', 'id')->all();
        $series_relacionado = \App\Serie::lists('titulo', 'id')->all();

        return view('cms::serie.detalhar', ['serie' => $serie, 'series_relacionado' => $series_relacionado, 'fontes' => $fontes]);
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
        $serie = $this->serie->where([
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

        $serie = $this->serie->where([
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

    public function testeExcel(){
        $data = Excel::load(public_path().'/excel/populacao.UF.FAIXA.ETARIA.15.29.MULHER.xlsx', function($reader) {

        })->get();

        //return $data;

        //dd($data);

        $registros = [];
        $valor = 0;
        $periodo = '';
        $uf = '';
        $municipio = '';
        $bairro = '';
        $serie_id = 12;
        $cms_user_id = 1;


        foreach($data as $row){
            foreach($row as $index => $cel){
                if(!empty($index)){
                    if($index=='uf'){
                        $uf = $cel;
                    }else{
                        array_push($registros, ['uf' => $uf, 'ano' => $index, 'value' => $cel]);
                        $valor = $cel;
                        $periodo = $index;
                        $reg =[
                            'valor' => $valor,
                            'periodo' => $periodo,
                            'uf' => $uf,
                            'municipio' => $municipio,
                            'bairro' => $bairro,
                            'serie_id' => $serie_id,
                            'cmsuser_id' => $cms_user_id
                        ];
                        $registro = \App\ValorSerie::create($reg);
                    }
                }
            }
        }

        /*foreach($data['RowCollection']->items as $row){
            foreach($row['RowCollection']->items as $index => $cel){
                if($index != ''){
                    if($index=='uf'){
                        $uf = $cel;
                    }else{
                        array_push($registros, ['uf' => $uf, 'ano' => $index, 'value' => $cel]);
                    }
                }
            }
        }*/

        dd($registros);
    }

    


}
