<?php

namespace Cms\Controllers;

use Cms\Models\ImagemCms;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Facades\Image;

class LinkController extends Controller
{
    
    

    public function __construct()
    {
        $this->link = new \App\Link;
        $this->campos = [
            'imagem', 'tipo', 'posicao', 'titulo', 'descricao', 'link', 'tags', 'cmsuser_id', 'idioma_sigla',
        ];
        $this->pathImagem = public_path().'/imagens/links';
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

        $links = \App\Link::all();
        $series = \App\Serie::lists('titulo', 'id')->all();
        $idiomas = \App\Idioma::lists('titulo', 'sigla')->all();

        return view('cms::link.listar', ['links' => $links, 'series' => $series, 'idiomas' => $idiomas]);
    }

    public function listar(Request $request)
    {

        //Log::info('CAMPOS: '.$request->campos);

        //Auth::loginUsingId(2);

        $campos = explode(", ", $request->campos);

        $links = DB::table('links')
            ->select($campos)
            ->where([
                [$request->campoPesquisa, 'like', "%$request->dadoPesquisa%"],
            ])
            ->orderBy($request->ordem, $request->sentido)
            ->paginate($request->itensPorPagina);
        return $links;
    }

    public function inserir(Request $request)
    {

        $data = $request->all();

        $data['link'] += ['cmsuser_id' => auth()->guard('cms')->user()->id];//adiciona id do usuario

        //verifica se o index do campo existe no array e caso não exista inserir o campo com valor vazio.
        foreach($this->campos as $campo){
            if(!array_key_exists($campo, $data)){
                $data['link'] += [$campo => ''];
            }
        }

        $file = $request->file('file');

        if($file!=null){
            $filename = rand(1000,9999)."-".clean($file->getClientOriginalName());
            $imagemCms = new ImagemCms();
            $success = $imagemCms->inserir($file, $this->pathImagem, $filename, $this->sizesImagem, $this->widthOriginal);
            
            if($success){
                $data['link']['imagem'] = $filename;
                return $this->link->create($data['link']);
            }else{
                return "erro";
            }
        }

        return $this->link->create($data['link']);

    }

    public function detalhar($id)
    {
        $link = $this->link->where([
            ['id', '=', $id],
        ])->firstOrFail();
        $series = \App\Serie::lists('titulo', 'id')->all();
        $idiomas = \App\Idioma::lists('titulo', 'sigla')->all();

        return view('cms::link.detalhar', ['link' => $link, 'series' => $series, 'idiomas' => $idiomas]);
    }

    public function alterar(Request $request, $id)
    {
        $data = $request->all();
        $data['link'] += ['cmsuser_id' => auth()->guard('cms')->user()->id];//adiciona id do usuario

        //verifica se o index do campo existe no array e caso não exista inserir o campo com valor vazio.
        foreach($this->campos as $campo){
            if(!array_key_exists($campo, $data)){
                if($campo!='imagem'){
                    $data['link'] += [$campo => ''];
                }
            }
        }
        $link = $this->link->where([
            ['id', '=', $id],
        ])->firstOrFail();

        $file = $request->file('file');

        if($file!=null){
            $filename = rand(1000,9999)."-".clean($file->getClientOriginalName());
            $imagemCms = new ImagemCms();
            $success = $imagemCms->alterar($file, $this->pathImagem, $filename, $this->sizesImagem, $this->widthOriginal, $link);
            if($success){
                $data['link']['imagem'] = $filename;
                $link->update($data['link']);
                return $link->imagem;
            }else{
                return "erro";
            }
        }

        //remover imagem
        if($data['removerImagem']){
            $data['link']['imagem'] = '';
            if(file_exists($this->pathImagem."/".$link->imagem)) {
                unlink($this->pathImagem . "/" . $link->imagem);
            }
        }

        $link->update($data['link']);
        return "Gravado com sucesso";
    }

    public function excluir($id)
    {
        //Auth::loginUsingId(2);

        $link = $this->link->where([
            ['id', '=', $id],
        ])->firstOrFail();

        //remover imagens        
        if(!empty($link->imagem)){
            //remover imagens
            $imagemCms = new ImagemCms();
            $imagemCms->excluir($this->pathImagem, $this->sizesImagem, $link);
        }

        $link->delete();

    }

    


}
