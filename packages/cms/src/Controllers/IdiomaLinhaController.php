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

class IdiomaLinhaController extends Controller
{
    
    

    public function __construct()
    {
        $this->idiomaLinha = new \App\IdiomaLinha;
        $this->campos = [
            'imagem', 'titulo', 'idioma_sigla',
        ];
        $this->pathImagem = public_path().'/imagens/idiomas_linhas';
        $this->sizesImagem = [
            'xs' => ['width' => 140, 'height' => 79],
            'sm' => ['width' => 480, 'height' => 270],
            'md' => ['width' => 580, 'height' => 326],
            'lg' => ['width' => 1170, 'height' => 658]
        ];
        $this->widthOriginal = true;
    }

    function index($linha_id)
    {
        $linha = \App\Linha::where('id', $linha_id)->first();
        $idiomas = \App\Idioma::lists('titulo', 'sigla')->all();

        return view('cms::idiomas_linhas.listar', ['linha_id' => $linha->id, 'idiomas' => $idiomas]);
    }

    public function listar(Request $request)
    {

        $campos = explode(", ", $request->campos);

        $linhas = DB::table('idiomas_linhas')
            ->select($campos)
            ->where([
                [$request->campoPesquisa, 'ilike', "%$request->dadoPesquisa%"],
            ])
            ->where('linha_id', $request->linha_id)
            ->orderBy($request->ordem, $request->sentido)
            ->paginate($request->itensPorPagina);
        return $linhas;
    }

    public function inserir(Request $request)
    {

        $data = $request->all();

        $data['linha'] += ['cmsuser_id' => auth()->guard('cms')->user()->id];//adiciona id do usuario

        /*if(empty($data['linha']['linha_id'])){
            $data['linha']['linha_id'] = 0;
        }*/

        //$data['linha']['tipo_valores'] = 0;

        //verifica se o index do campo existe no array e caso não exista inserir o campo com valor vazio.
        foreach($this->campos as $campo){
            if(!array_key_exists($campo, $data)){
                $data['linha'] += [$campo => ''];
            }
        }

        $file = $request->file('file');

        if($file!=null){
            $filename = rand(1000,9999)."-".clean($file->getClientOriginalName());
            $imagemCms = new ImagemCms();
            $success = $imagemCms->inserir($file, $this->pathImagem, $filename, $this->sizesImagem, $this->widthOriginal);
            
            if($success){
                $data['linha']['imagem'] = $filename;
                return $this->idiomaLinha->create($data['linha']);
            }else{
                return "erro";
            }
        }

        $linha = $this->idiomaLinha->create($data['linha']);

        return $linha;

    }

    public function detalhar($id)
    {
        $idioma_linha = $this->idiomaLinha
            ->where([
            ['id', '=', $id],
        ])->first();
        $idiomas = \App\Idioma::lists('titulo', 'sigla')->all();

        $linha_id = $idioma_linha->linha_id;

        return view('cms::idiomas_linhas.detalhar', [
            'idioma_linha' => $idioma_linha,
            'idiomas' => $idiomas,
            'linha_id' => $linha_id
        ]);
    }

    public function alterar(Request $request, $id)
    {
        $data = $request->all();
        $data['linha'] += ['cmsuser_id' => auth()->guard('cms')->user()->id];//adiciona id do usuario

        //verifica se o index do campo existe no array e caso não exista inserir o campo com valor vazio.
        foreach($this->campos as $campo){
            if(!array_key_exists($campo, $data)){
                if($campo!='imagem'){
                    $data['linha'] += [$campo => ''];
                }
            }
        }

	    $data['linha']['tipo_valores'] = 0;

        $linha = $this->idiomaLinha->where([
            ['id', '=', $id],
        ])->firstOrFail();

        $file = $request->file('file');

        if($file!=null){
            $filename = rand(1000,9999)."-".clean($file->getClientOriginalName());
            $imagemCms = new ImagemCms();
            $success = $imagemCms->alterar($file, $this->pathImagem, $filename, $this->sizesImagem, $this->widthOriginal, $linha);
            if($success){
                $data['linha']['imagem'] = $filename;
                $linha->update($data['linha']);
                return $linha->imagem;
            }else{
                return "erro";
            }
        }

        //remover imagem
        if($data['removerImagem']){
            $data['linha']['imagem'] = '';
            if(file_exists($this->pathImagem."/".$linha->imagem)) {
                unlink($this->pathImagem . "/" . $linha->imagem);
            }
        }

        $linha->update($data['linha']);
        return "Gravado com sucesso";
    }

    public function excluir($id)
    {
        //Auth::loginUsingId(2);

        $linha = $this->idiomaLinha->where([
            ['id', '=', $id],
        ])->firstOrFail();

        //remover imagens        
        if(!empty($linha->imagem)){
            //remover imagens
            $imagemCms = new ImagemCms();
            $imagemCms->excluir($this->pathImagem, $this->sizesImagem, $linha);
        }
                

        $linha->delete();

    }

    
    


}
