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
            'imagem', 'origem_id', 'titulo', 'descricao', 'autor', 'fonte', 'url', 'legenda', 'cmsuser_id',
        ];
        $this->pathImagem = public_path().'/imagens/artigos';
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

        $artigos = \App\Artigo::all();
        $links = \App\Link::lists('titulo', 'id')->all();

        return view('cms::artigo.listar', ['artigos' => $artigos, 'links' => $links]);
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

        //verifica se o index do campo existe no array e caso não exista inserir o campo com valor vazio.
        foreach($this->campos as $campo){
            if(!array_key_exists($campo, $data)){
                $data['artigo'] += [$campo => ''];
            }
        }

        $file = $request->file('file');

        if($file!=null){
            $filename = rand(1000,9999)."-".clean($file->getClientOriginalName());
            $imagemCms = new ImagemCms();
            $success = $imagemCms->inserir($file, $this->pathImagem, $filename, $this->sizesImagem, $this->widthOriginal);
            
            if($success){
                $data['artigo']['imagem'] = $filename;
                return $this->artigo->create($data['artigo']);
            }else{
                return "erro";
            }
        }

        return $this->artigo->create($data['artigo']);

    }

    public function detalhar($id)
    {
        $artigo = $this->artigo->where([
            ['id', '=', $id],
        ])->firstOrFail();
        $links = \App\Link::lists('titulo', 'id')->all();

        return view('cms::artigo.detalhar', ['artigo' => $artigo, 'links' => $links]);
    }

    public function alterar(Request $request, $id)
    {
        $data = $request->all();
        $data['artigo'] += ['cmsuser_id' => auth()->guard('cms')->user()->id];//adiciona id do usuario

        //verifica se o index do campo existe no array e caso não exista inserir o campo com valor vazio.
        foreach($this->campos as $campo){
            if(!array_key_exists($campo, $data)){
                if($campo!='imagem'){
                    $data['artigo'] += [$campo => ''];
                }
            }
        }
        $artigo = $this->artigo->where([
            ['id', '=', $id],
        ])->firstOrFail();

        $file = $request->file('file');

        if($file!=null){
            $filename = rand(1000,9999)."-".clean($file->getClientOriginalName());
            $imagemCms = new ImagemCms();
            $success = $imagemCms->alterar($file, $this->pathImagem, $filename, $this->sizesImagem, $this->widthOriginal, $artigo);
            if($success){
                $data['artigo']['imagem'] = $filename;
                $artigo->update($data['artigo']);
                return $artigo->imagem;
            }else{
                return "erro";
            }
        }

        //remover imagem
        if($data['removerImagem']){
            $data['artigo']['imagem'] = '';
            if(file_exists($this->pathImagem."/".$artigo->imagem)) {
                unlink($this->pathImagem . "/" . $artigo->imagem);
            }
        }

        $artigo->update($data['artigo']);
        return "Gravado com sucesso";
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
                

        $artigo->delete();

    }

    


}
