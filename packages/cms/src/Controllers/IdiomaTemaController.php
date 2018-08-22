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

class IdiomaTemaController extends Controller
{
    
    

    public function __construct()
    {
        $this->idiomaTema = new \App\IdiomaTema;
        $this->campos = [
            'imagem', 'titulo', 'idioma_sigla',
        ];
        $this->pathImagem = public_path().'/imagens/idiomas_temas';
        $this->sizesImagem = [
            'xs' => ['width' => 140, 'height' => 79],
            'sm' => ['width' => 480, 'height' => 270],
            'md' => ['width' => 580, 'height' => 326],
            'lg' => ['width' => 1170, 'height' => 658]
        ];
        $this->widthOriginal = true;
    }

    function index($tema_id)
    {
        $tema = \App\Tema::where('id', $tema_id)->first();
        $idiomas = \App\Idioma::lists('titulo', 'sigla')->all();

        return view('cms::idiomas_temas.listar', ['tema_id' => $tema->id, 'idiomas' => $idiomas]);
    }

    public function listar(Request $request)
    {

        $campos = explode(", ", $request->campos);

        $temas = DB::table('idiomas_temas')
            ->select($campos)
            ->where([
                [$request->campoPesquisa, 'ilike', "%$request->dadoPesquisa%"],
            ])
            ->where('tema_id', $request->tema_id)
            ->orderBy($request->ordem, $request->sentido)
            ->paginate($request->itensPorPagina);
        return $temas;
    }

    public function inserir(Request $request)
    {

        $data = $request->all();

        $data['tema'] += ['cmsuser_id' => auth()->guard('cms')->user()->id];//adiciona id do usuario

        /*if(empty($data['tema']['tema_id'])){
            $data['tema']['tema_id'] = 0;
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
                return $this->idiomaTema->create($data['tema']);
            }else{
                return "erro";
            }
        }

        $tema = $this->idiomaTema->create($data['tema']);

        return $tema;

    }

    public function detalhar($id)
    {
        $idioma_tema = $this->idiomaTema
            ->where([
            ['id', '=', $id],
        ])->first();
        $idiomas = \App\Idioma::lists('titulo', 'sigla')->all();

        $tema_id = $idioma_tema->tema_id;

        return view('cms::idiomas_temas.detalhar', [
            'idioma_tema' => $idioma_tema,
            'idiomas' => $idiomas,
            'tema_id' => $tema_id
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

        $tema = $this->idiomaTema->where([
            ['id', '=', $id],
        ])->firstOrFail();

        $file = $request->file('file');

        if($file!=null){
            $filename = rand(1000,9999)."-".clean($file->getClientOriginalName());
            $imagemCms = new ImagemCms();
            $success = $imagemCms->alterar($file, $this->pathImagem, $filename, $this->sizesImagem, $this->widthOriginal, $tema);
            if($success){
                $data['tema']['imagem'] = $filename;
                $tema->update($data['tema']);
                return $tema->imagem;
            }else{
                return "erro";
            }
        }

        //remover imagem
        if($data['removerImagem']){
            $data['tema']['imagem'] = '';
            if(file_exists($this->pathImagem."/".$tema->imagem)) {
                unlink($this->pathImagem . "/" . $tema->imagem);
            }
        }

        $tema->update($data['tema']);
        return "Gravado com sucesso";
    }

    public function excluir($id)
    {
        //Auth::loginUsingId(2);

        $tema = $this->idiomaTema->where([
            ['id', '=', $id],
        ])->firstOrFail();

        //remover imagens        
        if(!empty($tema->imagem)){
            //remover imagens
            $imagemCms = new ImagemCms();
            $imagemCms->excluir($this->pathImagem, $this->sizesImagem, $tema);
        }
                

        $tema->delete();

    }

    
    


}
