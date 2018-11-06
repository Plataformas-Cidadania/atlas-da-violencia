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

class IdiomaUnidadeController extends Controller
{
    
    

    public function __construct()
    {
        $this->idiomaUnidade = new \App\IdiomaUnidade;
        $this->campos = [
            'imagem', 'titulo', 'idioma_sigla',
        ];
        $this->pathImagem = public_path().'/imagens/idiomas_unidades';
        $this->sizesImagem = [
            'xs' => ['width' => 140, 'height' => 79],
            'sm' => ['width' => 480, 'height' => 270],
            'md' => ['width' => 580, 'height' => 326],
            'lg' => ['width' => 1170, 'height' => 658]
        ];
        $this->widthOriginal = true;
    }

    function index($unidade_id)
    {
        $unidade = \App\Unidade::where('id', $unidade_id)->first();
        $idiomas = \App\Idioma::lists('titulo', 'sigla')->all();

        return view('cms::idiomas_unidades.listar', ['unidade_id' => $unidade->id, 'idiomas' => $idiomas]);
    }

    public function listar(Request $request)
    {

        $campos = explode(", ", $request->campos);

        $unidades = DB::table('idiomas_unidades')
            ->select($campos)
            ->where([
                [$request->campoPesquisa, 'ilike', "%$request->dadoPesquisa%"],
            ])
            ->where('unidade_id', $request->unidade_id)
            ->orderBy($request->ordem, $request->sentido)
            ->paginate($request->itensPorPagina);
        return $unidades;
    }

    public function inserir(Request $request)
    {

        $data = $request->all();

        $data['unidade'] += ['cmsuser_id' => auth()->guard('cms')->user()->id];//adiciona id do usuario

        /*if(empty($data['unidade']['unidade_id'])){
            $data['unidade']['unidade_id'] = 0;
        }*/

        //$data['unidade']['tipo_valores'] = 0;

        //verifica se o index do campo existe no array e caso não exista inserir o campo com valor vazio.
        foreach($this->campos as $campo){
            if(!array_key_exists($campo, $data)){
                $data['unidade'] += [$campo => ''];
            }
        }

        $file = $request->file('file');

        if($file!=null){
            $filename = rand(1000,9999)."-".clean($file->getClientOriginalName());
            $imagemCms = new ImagemCms();
            $success = $imagemCms->inserir($file, $this->pathImagem, $filename, $this->sizesImagem, $this->widthOriginal);
            
            if($success){
                $data['unidade']['imagem'] = $filename;
                return $this->idiomaUnidade->create($data['unidade']);
            }else{
                return "erro";
            }
        }

        $unidade = $this->idiomaUnidade->create($data['unidade']);

        return $unidade;

    }

    public function detalhar($id)
    {
        $idioma_unidade = $this->idiomaUnidade
            ->where([
            ['id', '=', $id],
        ])->first();
        $idiomas = \App\Idioma::lists('titulo', 'sigla')->all();

        $unidade_id = $idioma_unidade->unidade_id;

        return view('cms::idiomas_unidades.detalhar', [
            'idioma_unidade' => $idioma_unidade,
            'idiomas' => $idiomas,
            'unidade_id' => $unidade_id
        ]);
    }

    public function alterar(Request $request, $id)
    {
        $data = $request->all();
        $data['unidade'] += ['cmsuser_id' => auth()->guard('cms')->user()->id];//adiciona id do usuario

        //verifica se o index do campo existe no array e caso não exista inserir o campo com valor vazio.
        foreach($this->campos as $campo){
            if(!array_key_exists($campo, $data)){
                if($campo!='imagem'){
                    $data['unidade'] += [$campo => ''];
                }
            }
        }

	    $data['unidade']['tipo_valores'] = 0;

        $unidade = $this->idiomaUnidade->where([
            ['id', '=', $id],
        ])->firstOrFail();

        $file = $request->file('file');

        if($file!=null){
            $filename = rand(1000,9999)."-".clean($file->getClientOriginalName());
            $imagemCms = new ImagemCms();
            $success = $imagemCms->alterar($file, $this->pathImagem, $filename, $this->sizesImagem, $this->widthOriginal, $unidade);
            if($success){
                $data['unidade']['imagem'] = $filename;
                $unidade->update($data['unidade']);
                return $unidade->imagem;
            }else{
                return "erro";
            }
        }

        //remover imagem
        if($data['removerImagem']){
            $data['unidade']['imagem'] = '';
            if(file_exists($this->pathImagem."/".$unidade->imagem)) {
                unlink($this->pathImagem . "/" . $unidade->imagem);
            }
        }

        $unidade->update($data['unidade']);
        return "Gravado com sucesso";
    }

    public function excluir($id)
    {
        //Auth::loginUsingId(2);

        $unidade = $this->idiomaUnidade->where([
            ['id', '=', $id],
        ])->firstOrFail();

        //remover imagens        
        if(!empty($unidade->imagem)){
            //remover imagens
            $imagemCms = new ImagemCms();
            $imagemCms->excluir($this->pathImagem, $this->sizesImagem, $unidade);
        }
                

        $unidade->delete();

    }

    
    


}
