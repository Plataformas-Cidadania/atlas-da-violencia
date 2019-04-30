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

class FiltroSerieController extends Controller
{
    
    private $subfiltros = [];

    public function __construct()
    {
        $this->filtroSerie = new \App\FiltroSerie;
        $this->campos = [
            'filtros_id', 'serie_id',
        ];
        $this->pathImagem = public_path().'/imagens/filtros_series';
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
        $filtros = \App\Filtro::pluck('titulo', 'id');

        //Log::info($this->subfiltros);

        return view('cms::filtros_series.listar', ['serie_id' => $serie->id, 'filtros' => $filtros]);
    }



    public function listar(Request $request)
    {

        $campos = explode(", ", $request->campos);

        $series = DB::table('filtros_series')
            ->select($campos)
            ->join('series', 'series.id', '=', 'filtros_series.serie_id')
            ->join('filtros', 'filtros.id', '=', 'filtros_series.filtro_id')
            /*->join('idiomas_filtros', 'idiomas_filtros.filtro_id', '=', 'filtros.id')*/
            ->where([
                [$request->campoPesquisa, 'ilike', "%$request->dadoPesquisa%"],
            ])
            ->where('filtros_series.serie_id', $request->serie_id)
            /*->where('idiomas_filtros.idioma_sigla', 'pt_BR')*/
            ->orderBy($request->ordem, $request->sentido)
            ->paginate($request->itensPorPagina);
        return $series;
    }

    public function inserir(Request $request)
    {

        $data = $request->all();

        $data['filtro'] += ['cmsuser_id' => auth()->guard('cms')->user()->id];//adiciona id do usuario

        /*if(empty($data['filtro']['serie_id'])){
            $data['filtro']['serie_id'] = 0;
        }*/

        //$data['filtro']['tipo_valores'] = 0;

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
                return $this->filtroSerie->create($data['filtro']);
            }else{
                return "erro";
            }
        }

        $serie = $this->filtroSerie->create($data['filtro']);

        return $serie;

    }

    public function detalhar($id)
    {
        $filtro_serie = $this->filtroSerie
            ->where([
            ['id', '=', $id],
        ])->first();
        $filtros = [];
        $filtros = $this->mountListFiltros($filtros, 0, "");

        $serie_id = $filtro_serie->serie_id;

        return view('cms::filtros_series.detalhar', [
            'filtro_serie' => $filtro_serie,
            'filtros' => $filtros,
            'serie_id' => $serie_id
        ]);
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

	    $data['filtro']['tipo_valores'] = 0;

        $serie = $this->filtroSerie->where([
            ['id', '=', $id],
        ])->firstOrFail();

        $file = $request->file('file');

        if($file!=null){
            $filename = rand(1000,9999)."-".clean($file->getClientOriginalName());
            $imagemCms = new ImagemCms();
            $success = $imagemCms->alterar($file, $this->pathImagem, $filename, $this->sizesImagem, $this->widthOriginal, $serie);
            if($success){
                $data['filtro']['imagem'] = $filename;
                $serie->update($data['filtro']);
                return $serie->imagem;
            }else{
                return "erro";
            }
        }

        //remover imagem
        if($data['removerImagem']){
            $data['filtro']['imagem'] = '';
            if(file_exists($this->pathImagem."/".$serie->imagem)) {
                unlink($this->pathImagem . "/" . $serie->imagem);
            }
        }

        $serie->update($data['filtro']);
        return "Gravado com sucesso";
    }

    public function excluir($id)
    {
        //Auth::loginUsingId(2);

        $serie = $this->filtroSerie->where([
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
