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

class FilterOptionAbrangenciaController extends Controller
{
    
    

    public function __construct()
    {
        $this->filterOptionAbrangencia = new \App\FilterOptionAbrangencia;
        $this->campos = [
            'title',
        ];
        $this->pathImagem = public_path().'/imagens/filters-options-abrangencias';
        $this->sizesImagem = [
            'xs' => ['width' => 140, 'height' => 79],
            'sm' => ['width' => 480, 'height' => 270],
            'md' => ['width' => 580, 'height' => 326],
            'lg' => ['width' => 1170, 'height' => 658]
        ];
        $this->widthOriginal = true;
    }

    function index($option_abrangencia_id)
    {
        $optionAbrangencia = \App\OptionAbrangencia::where('id', $option_abrangencia_id)->first();
        $filters = \App\FilterOptionAbrangencia::lists('title')->all();

        return view('cms::filters_options_abrangencias.listar', ['option_abrangencia_id' => $optionAbrangencia->id, 'filters' => $filters]);
    }

    public function listar(Request $request)
    {

        $campos = explode(", ", $request->campos);

        $optionsAbrangencias = DB::table('filters_options_abrangencias')
            ->select($campos)
            ->where([
                [$request->campoPesquisa, 'ilike', "%$request->dadoPesquisa%"],
            ])
            ->where('option_abrangencia_id', $request->option_abrangencia_id)
            ->orderBy($request->ordem, $request->sentido)
            ->paginate($request->itensPorPagina);
        return $optionsAbrangencias;
    }

    public function inserir(Request $request)
    {

        $data = $request->all();

        $data['optionAbrangencia'] += ['cmsuser_id' => auth()->guard('cms')->user()->id];//adiciona id do usuario

        /*if(empty($data['optionAbrangencia']['option_abrangencia_id'])){
            $data['optionAbrangencia']['option_abrangencia_id'] = 0;
        }*/

        //$data['optionAbrangencia']['tipo_valores'] = 0;

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
                return $this->filterOptionAbrangencia->create($data['optionAbrangencia']);
            }else{
                return "erro";
            }
        }

        $optionAbrangencia = $this->filterOptionAbrangencia->create($data['optionAbrangencia']);

        return $optionAbrangencia;

    }

    public function detalhar($id)
    {
        $filter_optionAbrangencia = $this->filterOptionAbrangencia
            ->where([
            ['id', '=', $id],
        ])->first();
        $filters = \App\FilterOptionAbrangencia::lists('title')->all();

        $option_abrangencia_id = $filter_optionAbrangencia->option_abrangencia_id;

        return view('cms::filters_options_abrangencias.detalhar', [
            'filter_optionAbrangencia' => $filter_optionAbrangencia,
            'filters' => $filters,
            'option_abrangencia_id' => $option_abrangencia_id
        ]);
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

	    $data['optionAbrangencia']['tipo_valores'] = 0;

        $optionAbrangencia = $this->filterOptionAbrangencia->where([
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

        $optionAbrangencia = $this->filterOptionAbrangencia->where([
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
