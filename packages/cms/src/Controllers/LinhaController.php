<?php

namespace Cms\Controllers;

use Cms\Models\ImagemCms;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Facades\Image;

class LinhaController extends Controller
{
    
    

    public function __construct()
    {
        $this->linha = new \App\Linha;
        $this->idiomaLinha = new \App\IdiomaLinha;
        $this->campos = [
            'imagem', 'icone', 'titulo', 'tipo', 'slug', 'cmsuser_id',
        ];
        $this->pathImagem = public_path().'/imagens/linhas';
        $this->sizesImagem = [
            'xs' => ['width' => 34, 'height' => 23],
            'sm' => ['width' => 50, 'height' => 34],
            'md' => ['width' => 100, 'height' => 68],
            'lg' => ['width' => 150, 'height' => 101]
        ];
        $this->pathIcone = public_path().'/imagens/linhas_icones';
        $this->sizesIcone = [
            'xs' => ['width' => 34, 'height' => 23],
            'sm' => ['width' => 50, 'height' => 34],
            'md' => ['width' => 100, 'height' => 68],
            'lg' => ['width' => 150, 'height' => 101]
        ];
        $this->widthOriginal = true;
    }

    function index($transporte_id=0)
    {

        $linhas = \App\Linha::all();
        $idiomas = \App\Idioma::lists('titulo', 'sigla')->all();

        return view('cms::linha.listar', ['linhas' => $linhas, 'idiomas' => $idiomas, 'transporte_id' => $transporte_id]);
    }

    public function listar(Request $request)
    {

        $campos = explode(", ", $request->campos);

        $linhas = DB::table('linhas')
        ->select($campos)
        ->join('idiomas_linhas', 'idiomas_linhas.linha_id', '=', 'linhas.id')
        ->where([
            [$request->campoPesquisa, 'like', "%$request->dadoPesquisa%"],
            ['idiomas_linhas.idioma_sigla', 'pt_BR'],
            ['transporte_id', $request->transporte_id],
        ])
        ->orderBy($request->ordem, $request->sentido)
        ->paginate($request->itensPorPagina);


        return $linhas;
    }

    public function inserir(Request $request)
    {

        $data = $request->all();

        //$data['linha'] = [];

        $data['linha'] += ['cmsuser_id' => auth()->guard('cms')->user()->id];//adiciona id do usuario
        $data['idioma'] += ['cmsuser_id' => auth()->guard('cms')->user()->id];//adiciona id do usuario

        //verifica se o index do campo existe no array e caso não exista inserir o campo com valor vazio.
        foreach($this->campos as $campo){
            if(!array_key_exists($campo, $data)){
                $data['linha'] += [$campo => ''];
            }
        }

        $file = $request->file('file');
        $fileIcone = $request->file('fileIcone');

        if($file!=null){
            $imagemCms = new ImagemCms();

            $filename = rand(1000,9999)."-".clean($file->getClientOriginalName());
            $success = $imagemCms->inserir($file, $this->pathImagem, $filename, $this->sizesImagem, $this->widthOriginal);

            $filenameIcone = rand(1000,9999)."-".clean($fileIcone->getClientOriginalName());
            $success = $imagemCms->inserir($fileIcone, $this->pathIcone, $filenameIcone, $this->sizesIcone, $this->widthOriginal);

            if($success){
                $data['linha']['imagem'] = $filename;
                $data['linha']['icone'] = $filenameIcone;
                $inserir = $this->linha->create($data['linha']);

                $data['idioma']['linha_id'] = $inserir->id;
                $inserir2 = $this->idiomaLinha->create($data['idioma']);

                return $inserir;
            }else{
                return "erro";
            }
        }

        //return $this->linha->create($data['linha']);

        $inserir = $this->linha->create($data['linha']);
        $data['idioma']['linha_id'] = $inserir->id;
        $inserir2 = $this->idiomaLinha->create($data['idioma']);
        return $inserir;

    }

    public function detalhar($id)
    {
        $linha = $this->linha->where([
            ['id', '=', $id],
        ])->firstOrFail();
        return view('cms::linha.detalhar', ['linha' => $linha]);
    }

    public function alterar(Request $request, $id)
    {



        $data = $request->all();
        //$data['linha'] = [];
        $data['linha'] += ['cmsuser_id' => auth()->guard('cms')->user()->id];//adiciona id do usuario

        //verifica se o index do campo existe no array e caso não exista inserir o campo com valor vazio.
        foreach($this->campos as $campo){
            if(!array_key_exists($campo, $data)){
                if($campo!='imagem' && $campo!='icone'){
                    $data['linha'] += [$campo => ''];
                }
            }
        }
        $linha = $this->linha->where([
            ['id', '=', $id],
        ])->firstOrFail();

        $file = $request->file('file');
        $fileIcone = $request->file('fileIcone');

        /*if($file!=null){
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
        }*/

        $successFile = true;
        if($file!=null){
            $filename = rand(1000,9999)."-".clean($file->getClientOriginalName());
            $imagemCms = new ImagemCms();
            $successFile = $imagemCms->alterar($file, $this->pathImagem, $filename, $this->sizesImagem, $this->widthOriginal, $linha);
            if($successFile){
                $data['linha']['imagem'] = $filename;
            }
        }
        $successFileIcone = true;
        if($fileIcone!=null){
            $filenameIcone = rand(1000,9999)."-".clean($fileIcone->getClientOriginalName());
            $imagemCms = new ImagemCms();
            $successFileIcone = $imagemCms->alterar($fileIcone, $this->pathIcone, $filenameIcone, $this->sizesIcone, $this->widthOriginal, $linha);
            if($successFileIcone){
                $data['linha']['icone'] = $filenameIcone;
            }
        }

        //remover imagem
        if($data['removerImagem']){
            $data['linha']['imagem'] = '';
            if(file_exists($this->pathImagem."/".$linha->imagem)) {
                unlink($this->pathImagem . "/" . $linha->imagem);
            }
        }
        //remover icone
        if($data['removerIcone']){
            $data['transporte']['icone'] = '';
            if(file_exists($this->pathIcone."/".$linha->icone)) {
                unlink($this->pathIcone . "/" . $linha->icone);
            }
        }

        if($successFile && $successFileIcone){
            $linha->update($data['linha']);
            return $linha->imagem;
        }else{
            return "erro";
        }

        /*$linha->update($data['linha']);
        return "Gravado com sucesso";*/
    }

    public function excluir($id)
    {
        //Auth::loginUsingId(2);

        $linha = $this->linha->where([
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
