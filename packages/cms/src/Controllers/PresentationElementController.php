<?php

namespace Cms\Controllers;

use Cms\Models\ImagemCms;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Facades\Image;

class PresentationElementController extends Controller
{

    public function __construct()
    {
        $this->element = new \App\PresentationElement();
        $this->campos = [
            'type', 'content', 'row', 'position', 'presentation_id', 'cmsuser_id',
        ];
        $this->pathImagem = public_path().'/imagens/presentation-elements';
        $this->sizesImagem = [
            'xs' => ['width' => 140, 'height' => 79],
            'sm' => ['width' => 480, 'height' => 270],
            'md' => ['width' => 580, 'height' => 326],
            'lg' => ['width' => 1170, 'height' => 658]
        ];
        $this->widthOriginal = true;

        $this->pathArquivo = public_path().'/arquivos/presentation-elements';
    }

    function index($presentation_id)
    {
        $elements = \App\PresentationElement::all();
        $idiomas = \App\Idioma::lists('titulo', 'sigla')->all();

        return view('cms::presentation_element.listar', ['elements' => $elements, 'presentation_id' => $presentation_id, 'idiomas' => $idiomas]);
    }

    public function listar(Request $request)
    {

        $campos = explode(", ", $request->campos);

        $elements = DB::table('presentations_elements')
            ->select($campos)
            ->where([
                [$request->campoPesquisa, 'ilike', "%$request->dadoPesquisa%"],
                ['presentation_id', $request->presentation_id],
            ])
            ->orderBy($request->ordem, $request->sentido)
            ->paginate($request->itensPorPagina);
        return [
            'elements' => $elements,
            'types' => config('constants.TYPES_PRESENTATION_ELEMENTS'),
            'chart_types' => config('constants.CHART_TYPES_PRESENTATION_ELEMENTS'),
            'status' => ['Inativo', 'Ativo']
        ];
    }


    public function inserir(Request $request)
    {

        $data = $request->all();

        $data['element'] += ['cmsuser_id' => auth()->guard('cms')->user()->id];//adiciona id do usuario

        //verifica se o index do campo existe no array e caso não exista inserir o campo com valor vazio.
        foreach($this->campos as $campo){
            if(!array_key_exists($campo, $data)){
                $data['element'] += [$campo => ''];
            }
        }

        $file = $request->file('file');
        $arquivo = $request->file('arquivo');


        $successFile = true;
        if($file!=null){
            $filename = rand(1000,9999)."-".clean($file->getClientOriginalName());
            $imagemCms = new ImagemCms();
            $successFile = $imagemCms->inserir($file, $this->pathImagem, $filename, $this->sizesImagem, $this->widthOriginal);
            if($successFile){
                $data['element']['content'] = $filename;
            }
        }

        $successArquivo = true;
        if($arquivo!=null){
            $filenameArquivo = rand(1000,9999)."-".clean($arquivo->getClientOriginalName());
            $successArquivo = $arquivo->move($this->pathArquivo, $filenameArquivo);
            if($successArquivo){
                $data['element']['content'] = $filenameArquivo;
            }
        }


        if($successFile && $successArquivo){
            return $this->element->create($data['element']);
        }else{
            return "erro";
        }


        return $this->element->create($data['element']);

    }

    public function detalhar($id)
    {
        $element = $this->element->where([
            ['id', '=', $id],
        ])->firstOrFail();
        $idiomas = \App\Idioma::lists('titulo', 'sigla')->all();

        $presentation_id = $element->presentation_id;

        //return $element;

        return view('cms::presentation_element.detalhar', ['element' => $element, 'presentation_id' => $presentation_id, 'idiomas' => $idiomas]);
    }

    public function alterar(Request $request, $id)
    {
        $data = $request->all();

        //return $data;

        $data['element'] += ['cmsuser_id' => auth()->guard('cms')->user()->id];//adiciona id do usuario

        //verifica se o index do campo existe no array e caso não exista inserir o campo com valor vazio.
        foreach($this->campos as $campo){
            if(!array_key_exists($campo, $data)){
                if($campo!='imagem' && $campo!='arquivo'){
                    $data['element'] += [$campo => ''];
                }
            }
        }
        $element = $this->element->where([
            ['id', '=', $id],
        ])->firstOrFail();


        $file = $request->file('file');
        $arquivo = $request->file('arquivo');

        //remover imagem
        if($data['removerImagem']){
            $data['element']['content'] = '';
            if(file_exists($this->pathImagem."/".$element->content)) {
                unlink($this->pathImagem . "/" . $element->content);
            }
        }


        if($data['removerArquivo']){
            $data['element']['content'] = '';
            if(file_exists($this->pathArquivo."/".$element->content)) {
                unlink($this->pathArquivo . "/" . $element->content);
            }
        }


        $successFile = true;
        if($file!=null){
            $filename = rand(1000,9999)."-".clean($file->getClientOriginalName());
            $imagemCms = new ImagemCms();
            $successFile = $imagemCms->alterar($file, $this->pathImagem, $filename, $this->sizesImagem, $this->widthOriginal, $element);
            if($successFile){
                $data['element']['content'] = $filename;
            }
        }

        $successArquivo = true;
        if($arquivo!=null){
            $filenameArquivo = rand(1000,9999)."-".clean($arquivo->getClientOriginalName());
            $successArquivo = $arquivo->move($this->pathArquivo, $filenameArquivo);
            if($successArquivo){
                $data['element']['content'] = $filenameArquivo;
            }
        }

        if($successFile && $successArquivo){

            $element->update($data['element']);
            return $element->imagem;
        }else{
            return "erro";
        }

        //$element->update($data['element']);
        //return "Gravado com sucesso";
    }

    public function excluir($id)
    {
        //Auth::loginUsingId(2);

        $element = $this->element->where([
            ['id', '=', $id],
        ])->firstOrFail();

        //remover imagens        
        if(!empty($element->imagem)){
            //remover imagens
            $imagemCms = new ImagemCms();
            $imagemCms->excluir($this->pathImagem, $this->sizesImagem, $element);
        }


        if(!empty($element->arquivo)) {
            if (file_exists($this->pathArquivo . "/" . $element->arquivo)) {
                unlink($this->pathArquivo . "/" . $element->arquivo);
            }
        }

        $element->delete();

    }


    public function status($id)
    {
        $tipo_atual = DB::table('presentations_elements')->where('id', $id)->first();
        $status = $tipo_atual->status == 0 ? 1 : 0;
        DB::table('presentations_elements')->where('id', $id)->update(['status' => $status]);

    }

}
