<?php

namespace Cms\Controllers;

use Cms\Models\ImagemCms;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Facades\Image;

class PresentationController extends Controller
{
    
    

    public function __construct()
    {
        $this->presentation = new \App\Presentation;
        $this->campos = [
            'slug', 'cmsuser_id'
        ];
        $this->pathImagem = public_path().'/imagens/presentations';
        $this->sizesImagem = [
            'xs' => ['width' => 140, 'height' => 79],
            'sm' => ['width' => 480, 'height' => 270],
            'md' => ['width' => 580, 'height' => 326],
            'lg' => ['width' => 1170, 'height' => 658]
        ];
        $this->widthOriginal = true;

        $this->pathArquivo = public_path().'/arquivos/presentations';
    }

    function index()
    {

        $presentations = \App\Presentation::all();
        $links = \App\Link::lists('titulo', 'id')->all();
        //$authors = \App\Author::lists('titulo', 'id')->all();
        $authors = \App\Author::pluck('titulo', 'id')->all();
        $idiomas = \App\Idioma::lists('titulo', 'sigla')->all();

        return view('cms::presentation.listar', ['presentations' => $presentations, 'links' => $links, 'authors' => $authors, 'idiomas' => $idiomas]);
    }

    public function listar(Request $request)
    {

        //Log::info('CAMPOS: '.$request->campos);

        //Auth::loginUsingId(2);

        $campos = explode(", ", $request->campos);

        $presentations = DB::table('presentations')
            ->select($campos)
            ->where([
                [$request->campoPesquisa, 'like', "%$request->dadoPesquisa%"],
            ])
            ->orderBy($request->ordem, $request->sentido)
            ->paginate($request->itensPorPagina);
        return $presentations;
    }

    public function inserir(Request $request)
    {

        $data = $request->all();

        $data['presentation'] += ['cmsuser_id' => auth()->guard('cms')->user()->id];//adiciona id do usuario

        //verifica se o index do campo existe no array e caso não exista inserir o campo com valor vazio.
        foreach($this->campos as $campo){
            if(!array_key_exists($campo, $data)){
                $data['presentation'] += [$campo => ''];
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
                $data['presentation']['imagem'] = $filename;
            }
        }

        $successArquivo = true;
        if($arquivo!=null){
            $filenameArquivo = rand(1000,9999)."-".clean($arquivo->getClientOriginalName());
            $successArquivo = $arquivo->move($this->pathArquivo, $filenameArquivo);
            if($successArquivo){
                $data['presentation']['arquivo'] = $filenameArquivo;
            }
        }

        if($successFile && $successArquivo){

            $insert = $this->presentation->create($data['presentation']);


            return $insert;

        }else{
            return "erro";
        }




        //return $this->presentation->create($data['presentation']);

    }

    public function detalhar($id)
    {
        $presentation = $this->presentation->where([
            ['id', '=', $id],
        ])->firstOrFail();
        $links = \App\Link::lists('titulo', 'id')->all();
        //$authors = \App\Author::lists('titulo', 'id')->all();
        $authors = \App\Author::pluck('titulo', 'id')->all();
        $idiomas = \App\Idioma::lists('titulo', 'sigla')->all();

        $autors_presentation = \App\AuthorPresentation::where('presentation_id', $id)->get();

        //transforma os dados da tabela autors presentation em atributos de presentation com o nome do checkbox no form
        foreach($autors_presentation as $row){
            $presentation->{"autor".$row->author_id} = true; //Ex.: $presentation->autor1
        }
        
        return view('cms::presentation.detalhar', ['presentation' => $presentation, 'links' => $links, 'authors' => $authors, 'idiomas' => $idiomas]);
    }

    public function alterar(Request $request, $id)
    {
        $data = $request->all();
        $data['presentation'] += ['cmsuser_id' => auth()->guard('cms')->user()->id];//adiciona id do usuario

        //verifica se o index do campo existe no array e caso não exista inserir o campo com valor vazio.
        foreach($this->campos as $campo){
            if(!array_key_exists($campo, $data)){
                if($campo!='imagem' && $campo!='arquivo'){
                    $data['presentation'] += [$campo => ''];
                }
            }
        }
        $presentation = $this->presentation->where([
            ['id', '=', $id],
        ])->firstOrFail();

        $file = $request->file('file');
        $arquivo = $request->file('arquivo');

        //remover imagem
        if($data['removerImagem']){
            $data['presentation']['imagem'] = '';
            if(file_exists($this->pathImagem."/".$presentation->imagem)) {
                unlink($this->pathImagem . "/" . $presentation->imagem);
            }
        }


        if($data['removerArquivo']){
            $data['presentation']['arquivo'] = '';
            if(file_exists($this->pathArquivo."/".$presentation->arquivo)) {
                unlink($this->pathArquivo . "/" . $presentation->arquivo);
            }
        }



        $successFile = true;
        if($file!=null){
            $filename = rand(1000,9999)."-".clean($file->getClientOriginalName());
            $imagemCms = new ImagemCms();
            $successFile = $imagemCms->alterar($file, $this->pathImagem, $filename, $this->sizesImagem, $this->widthOriginal, $presentation);
            if($successFile){
                $data['presentation']['imagem'] = $filename;
            }
        }

        $successArquivo = true;
        if($arquivo!=null){
            $filenameArquivo = rand(1000,9999)."-".clean($arquivo->getClientOriginalName());
            $successArquivo = $arquivo->move($this->pathArquivo, $filenameArquivo);
            if($successArquivo){
                $data['presentation']['arquivo'] = $filenameArquivo;
            }
        }

        if($successFile && $successArquivo){

            $presentation->update($data['presentation']);

            $authorPresentation = new \App\AuthorPresentation;
            DB::table('author_presentation')->where('presentation_id', $id)->delete();
            $dadosAuthorPresentation = Array();
            $dadosAuthorPresentation['presentation_id'] = $id;
            foreach($data["author_presentation"] as $autor => $marcado){
                if($marcado){
                    $array_autor = explode('_', $autor);
                    $dadosAuthorPresentation['author_id'] = $array_autor[1];
                    $authorPresentation->create($dadosAuthorPresentation);
                }
            }


            return $presentation->imagem;
        }else{
            return "erro";
        }
    }

    public function excluir($id)
    {
        //Auth::loginUsingId(2);

        $presentation = $this->presentation->where([
            ['id', '=', $id],
        ])->firstOrFail();

        //remover imagens        
        if(!empty($presentation->imagem)){
            //remover imagens
            $imagemCms = new ImagemCms();
            $imagemCms->excluir($this->pathImagem, $this->sizesImagem, $presentation);
        }


        if(!empty($presentation->arquivo)) {
            if (file_exists($this->pathArquivo . "/" . $presentation->arquivo)) {
                unlink($this->pathArquivo . "/" . $presentation->arquivo);
            }
        }
                

        $presentation->delete();

    }

    


}
