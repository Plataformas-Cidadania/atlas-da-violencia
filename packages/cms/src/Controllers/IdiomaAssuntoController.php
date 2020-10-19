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

class IdiomaAssuntoController extends Controller
{
    
    

    public function __construct()
    {
        $this->idiomaAssunto = new \App\IdiomaAssunto;
        $this->campos = [
            'imagem', 'titulo', 'idioma_sigla',
        ];
        $this->pathImagem = public_path().'/imagens/idiomas_assuntos';
        $this->sizesImagem = [
            'xs' => ['width' => 140, 'height' => 79],
            'sm' => ['width' => 480, 'height' => 270],
            'md' => ['width' => 580, 'height' => 326],
            'lg' => ['width' => 1170, 'height' => 658]
        ];
        $this->widthOriginal = true;
    }

    function index($assunto_id)
    {
        $assunto = \App\Assunto::where('id', $assunto_id)->first();
        $idiomas = \App\Idioma::lists('titulo', 'sigla')->all();

        return view('cms::idiomas_assuntos.listar', ['assunto_id' => $assunto->id, 'idiomas' => $idiomas]);
    }

    public function listar(Request $request)
    {

        $campos = explode(", ", $request->campos);

        $assuntos = DB::table('idiomas_assuntos')
            ->select($campos)
            ->where([
                [$request->campoPesquisa, 'ilike', "%$request->dadoPesquisa%"],
            ])
            ->where('assunto_id', $request->assunto_id)
            ->orderBy($request->ordem, $request->sentido)
            ->paginate($request->itensPorPagina);
        return $assuntos;
    }

    public function inserir(Request $request)
    {

        $data = $request->all();

        $data['assunto'] += ['cmsuser_id' => auth()->guard('cms')->user()->id];//adiciona id do usuario

        /*if(empty($data['assunto']['assunto_id'])){
            $data['assunto']['assunto_id'] = 0;
        }*/

        //$data['assunto']['tipo_valores'] = 0;

        //verifica se o index do campo existe no array e caso não exista inserir o campo com valor vazio.
        foreach($this->campos as $campo){
            if(!array_key_exists($campo, $data)){
                $data['assunto'] += [$campo => ''];
            }
        }

        $file = $request->file('file');

        if($file!=null){
            $filename = rand(1000,9999)."-".clean($file->getClientOriginalName());
            $imagemCms = new ImagemCms();
            $success = $imagemCms->inserir($file, $this->pathImagem, $filename, $this->sizesImagem, $this->widthOriginal);
            
            if($success){
                $data['assunto']['imagem'] = $filename;
                return $this->idiomaAssunto->create($data['assunto']);
            }else{
                return "erro";
            }
        }

        $assunto = $this->idiomaAssunto->create($data['assunto']);

        return $assunto;

    }

    public function detalhar($id)
    {
        $idioma_assunto = $this->idiomaAssunto
            ->where([
            ['id', '=', $id],
        ])->first();
        $idiomas = \App\Idioma::lists('titulo', 'sigla')->all();

        $assunto_id = $idioma_assunto->assunto_id;

        return view('cms::idiomas_assuntos.detalhar', [
            'idioma_assunto' => $idioma_assunto,
            'idiomas' => $idiomas,
            'assunto_id' => $assunto_id
        ]);
    }

    public function alterar(Request $request, $id)
    {
        $data = $request->all();
        $data['assunto'] += ['cmsuser_id' => auth()->guard('cms')->user()->id];//adiciona id do usuario

        //verifica se o index do campo existe no array e caso não exista inserir o campo com valor vazio.
        foreach($this->campos as $campo){
            if(!array_key_exists($campo, $data)){
                if($campo!='imagem'){
                    $data['assunto'] += [$campo => ''];
                }
            }
        }

	    $data['assunto']['tipo_valores'] = 0;

        $assunto = $this->idiomaAssunto->where([
            ['id', '=', $id],
        ])->firstOrFail();

        $file = $request->file('file');

        if($file!=null){
            $filename = rand(1000,9999)."-".clean($file->getClientOriginalName());
            $imagemCms = new ImagemCms();
            $success = $imagemCms->alterar($file, $this->pathImagem, $filename, $this->sizesImagem, $this->widthOriginal, $assunto);
            if($success){
                $data['assunto']['imagem'] = $filename;
                $assunto->update($data['assunto']);
                return $assunto->imagem;
            }else{
                return "erro";
            }
        }

        //remover imagem
        if($data['removerImagem']){
            $data['assunto']['imagem'] = '';
            if(file_exists($this->pathImagem."/".$assunto->imagem)) {
                unlink($this->pathImagem . "/" . $assunto->imagem);
            }
        }

        $assunto->update($data['assunto']);
        return "Gravado com sucesso";
    }

    public function excluir($id)
    {
        //Auth::loginUsingId(2);

        $assunto = $this->idiomaAssunto->where([
            ['id', '=', $id],
        ])->firstOrFail();

        //remover imagens        
        if(!empty($assunto->imagem)){
            //remover imagens
            $imagemCms = new ImagemCms();
            $imagemCms->excluir($this->pathImagem, $this->sizesImagem, $assunto);
        }
                

        $assunto->delete();

    }

    
    


}
