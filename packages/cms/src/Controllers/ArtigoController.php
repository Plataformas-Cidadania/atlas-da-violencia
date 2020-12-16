<?php

namespace Cms\Controllers;

use Cms\Models\ImagemCms;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Facades\Image;

class ArtigoController extends Controller
{
    
    

    public function __construct()
    {
        $this->artigo = new \App\Artigo;
        $this->campos = [
            'imagem', 'origem_id', 'titulo', 'descricao', 'autor', 'fonte', 'url', 'link', 'arquivo', 'legenda', 'cmsuser_id', 'idioma_sigla',
        ];
        $this->pathImagem = public_path().'/imagens/artigos';
        $this->sizesImagem = [
            'xs' => ['width' => 140, 'height' => 79],
            'sm' => ['width' => 480, 'height' => 270],
            'md' => ['width' => 580, 'height' => 326],
            'lg' => ['width' => 1170, 'height' => 658]
        ];
        $this->widthOriginal = true;

        $this->pathArquivo = public_path().'/arquivos/artigos';
    }

    function index()
    {

        $artigos = \App\Artigo::all();
        $links = \App\Link::lists('titulo', 'id')->all();
        //$authors = \App\Author::lists('titulo', 'id')->all();
        $authors = \App\Author::pluck('titulo', 'id')->all();
        $idiomas = \App\Idioma::lists('titulo', 'sigla')->all();
        $fontes = \App\Fonte::lists('titulo', 'id')->all();
        $assuntos = \App\Assunto::select('idiomas_assuntos.titulo', 'assuntos.id')
            ->join('idiomas_assuntos', 'idiomas_assuntos.assunto_id', '=', 'assuntos.id')
            ->where('idiomas_assuntos.idioma_sigla', 'pt_BR')
            ->get();

        return view('cms::artigo.listar', [
            'artigos' => $artigos,
                'links' => $links,
                'authors' => $authors,
                'idiomas' => $idiomas,
                'fontes' => $fontes,
                'assuntos' => $assuntos,
            ]);
    }

    public function listar(Request $request)
    {

        //Log::info('CAMPOS: '.$request->campos);

        //Auth::loginUsingId(2);

        $campos = explode(", ", $request->campos);

        $artigos = DB::table('artigos')
            ->select($campos)
            ->where([
                [$request->campoPesquisa, 'like', "%$request->dadoPesquisa%"],
            ])
            ->orderBy($request->ordem, $request->sentido)
            ->paginate($request->itensPorPagina);
        return $artigos;
    }

    public function inserir(Request $request)
    {

        $data = $request->all();

        $data['artigo'] += ['cmsuser_id' => auth()->guard('cms')->user()->id];//adiciona id do usuario


        if(!array_key_exists('origem_id', $data['artigo'])){
            $data['artigo']['origem_id'] = 0;
        }

        if($data['artigo']['origem_id']==''){
            $data['artigo']['origem_id'] = 0;
        }

        //verifica se o index do campo existe no array e caso não exista inserir o campo com valor vazio.
        foreach($this->campos as $campo){
            if(!array_key_exists($campo, $data)){
                $data['artigo'] += [$campo => ''];
            }
        }

        if(!array_key_exists('origem_id', $data['artigo'])){
            $dada['artigo']['origem_id'] = 0;
        }



        $file = $request->file('file');
        $arquivo = $request->file('arquivo');

        $successFile = true;
        if($file!=null){
            $filename = rand(1000,9999)."-".clean($file->getClientOriginalName());
            $imagemCms = new ImagemCms();
            $successFile = $imagemCms->inserir($file, $this->pathImagem, $filename, $this->sizesImagem, $this->widthOriginal);
            if($successFile){
                $data['artigo']['imagem'] = $filename;
            }
        }

        $successArquivo = true;
        if($arquivo!=null){
            $filenameArquivo = rand(1000,9999)."-".clean($arquivo->getClientOriginalName());
            $successArquivo = $arquivo->move($this->pathArquivo, $filenameArquivo);
            if($successArquivo){
                $data['artigo']['arquivo'] = $filenameArquivo;
            }
        }

        if($successFile && $successArquivo){

            $insert = $this->artigo->create($data['artigo']);

            $authorArtigo = new \App\AuthorArtigo;
            $dadosAuthorArtigo = Array();
            $dadosAuthorArtigo['artigo_id'] = $insert->id;

            if($data['artigo']['publicacao_atlas']!=1){
                foreach($data["author_artigo"] as $autor => $marcado){
                    if($marcado=='true'){
                        $array_autor = explode('_', $autor);
                        $dadosAuthorArtigo['author_id'] = $array_autor[1];
                        $authorArtigo->create($dadosAuthorArtigo);
                    }
                }
            }

            $assuntoArtigo = new \App\AssuntoArtigo;
            $dadosAssuntoArtigo = Array();
            $dadosAssuntoArtigo['artigo_id'] = $insert->id;

            foreach($data["assunto_artigo"] as $assunto => $marcado){
                if($marcado=='true'){
                    $array_assunto = explode('_', $assunto);
                    $dadosAssuntoArtigo['assunto_id'] = $array_assunto[1];
                    $assuntoArtigo->create($dadosAssuntoArtigo);
                }
            }

            return $insert;

        }else{
            return "erro";
        }




        //return $this->artigo->create($data['artigo']);

    }

    public function detalhar($id)
    {
        $artigo = $this->artigo->where([
            ['id', '=', $id],
        ])->firstOrFail();
        $links = \App\Link::lists('titulo', 'id')->all();
        //$authors = \App\Author::lists('titulo', 'id')->all();
        $authors = \App\Author::pluck('titulo', 'id')->all();
        $idiomas = \App\Idioma::lists('titulo', 'sigla')->all();
        $fontes = \App\Fonte::lists('titulo', 'id')->all();
        $assuntos = \App\Assunto::select('idiomas_assuntos.titulo', 'assuntos.id')
            ->join('idiomas_assuntos', 'idiomas_assuntos.assunto_id', '=', 'assuntos.id')
            ->where('idiomas_assuntos.idioma_sigla', 'pt_BR')
            ->get();


        $autors_artigo = \App\AuthorArtigo::where('artigo_id', $id)->get();

        //transforma os dados da tabela autors artigo em atributos de artigo com o nome do checkbox no form
        foreach($autors_artigo as $row){
            $artigo->{"autor".$row->author_id} = true; //Ex.: $artigo->autor1
        }

        $assuntos_artigo = \App\AssuntoArtigo::where('artigo_id', $id)->get();

        //transforma os dados da tabela assuntos artigo em atributos de artigo com o nome do checkbox no form
        foreach($assuntos_artigo as $row){
            $artigo->{"assunto".$row->assunto_id} = true; //Ex.: $artigo->assunto1
        }
        
        return view('cms::artigo.detalhar', [
            'artigo' => $artigo,
            'links' => $links,
            'authors' => $authors,
            'idiomas' => $idiomas,
            'fontes' => $fontes,
            'assuntos' => $assuntos,
        ]);
    }

    public function alterar(Request $request, $id)
    {
        $data = $request->all();
        $data['artigo'] += ['cmsuser_id' => auth()->guard('cms')->user()->id];//adiciona id do usuario

        if($data['artigo']['origem_id']==''){
            $data['artigo']['origem_id'] = 0;
        }

        //verifica se o index do campo existe no array e caso não exista inserir o campo com valor vazio.
        foreach($this->campos as $campo){
            if(!array_key_exists($campo, $data)){
                if($campo!='imagem' && $campo!='arquivo'){
                    $data['artigo'] += [$campo => ''];
                }
            }
        }
        $artigo = $this->artigo->where([
            ['id', '=', $id],
        ])->firstOrFail();

        $file = $request->file('file');
        $arquivo = $request->file('arquivo');

        //remover imagem
        if($data['removerImagem']==1){
            $data['artigo']['imagem'] = '';
            if(file_exists($this->pathImagem."/".$artigo->imagem)) {
                unlink($this->pathImagem . "/" . $artigo->imagem);
            }
        }


        if($data['removerArquivo']==1){
            $data['artigo']['arquivo'] = '';
            if(file_exists($this->pathArquivo."/".$artigo->arquivo)) {
                unlink($this->pathArquivo . "/" . $artigo->arquivo);
            }
        }



        $successFile = true;
        if($file!=null){
            $filename = rand(1000,9999)."-".clean($file->getClientOriginalName());
            $imagemCms = new ImagemCms();
            $successFile = $imagemCms->alterar($file, $this->pathImagem, $filename, $this->sizesImagem, $this->widthOriginal, $artigo);
            if($successFile){
                $data['artigo']['imagem'] = $filename;
            }
        }

        $successArquivo = true;
        if($arquivo!=null){
            $filenameArquivo = rand(1000,9999)."-".clean($arquivo->getClientOriginalName());
            $successArquivo = $arquivo->move($this->pathArquivo, $filenameArquivo);
            if($successArquivo){
                $data['artigo']['arquivo'] = $filenameArquivo;
            }
        }

        if($successFile && $successArquivo){

            $artigo->update($data['artigo']);

            $authorArtigo = new \App\AuthorArtigo;
            DB::table('author_artigo')->where('artigo_id', $id)->delete();
            $dadosAuthorArtigo = Array();
            $dadosAuthorArtigo['artigo_id'] = $id;
            if($data['artigo']['publicacao_atlas']!=1) {
                foreach ($data["author_artigo"] as $autor => $marcado) {
                    if ($marcado == 'true') {
                        $array_autor = explode('_', $autor);
                        $dadosAuthorArtigo['author_id'] = $array_autor[1];
                        $authorArtigo->create($dadosAuthorArtigo);
                    }
                }
            }

            $assuntoArtigo = new \App\AssuntoArtigo;
            DB::table('assuntos_artigos')->where('artigo_id', $id)->delete();
            $dadosAssuntoArtigo = Array();
            $dadosAssuntoArtigo['artigo_id'] = $id;
            foreach($data["assunto_artigo"] as $assunto => $marcado){
                if($marcado=='true'){
                    $array_assunto = explode('_', $assunto);
                    $dadosAssuntoArtigo['assunto_id'] = $array_assunto[1];
                    $assuntoArtigo->create($dadosAssuntoArtigo);
                }
            }


            return $artigo->imagem;
        }else{
            return "erro";
        }
    }

    public function excluir($id)
    {
        //Auth::loginUsingId(2);

        $artigo = $this->artigo->where([
            ['id', '=', $id],
        ])->firstOrFail();

        //remover imagens        
        if(!empty($artigo->imagem)){
            //remover imagens
            $imagemCms = new ImagemCms();
            $imagemCms->excluir($this->pathImagem, $this->sizesImagem, $artigo);
        }


        if(!empty($artigo->arquivo)) {
            if (file_exists($this->pathArquivo . "/" . $artigo->arquivo)) {
                unlink($this->pathArquivo . "/" . $artigo->arquivo);
            }
        }
                

        $artigo->delete();

    }

    


}
