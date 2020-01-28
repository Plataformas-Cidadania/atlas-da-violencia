<?php

namespace Cms\Controllers;

use Cms\Models\ImagemCms;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Facades\Image;

class TransporteController extends Controller
{
    

    public function __construct()
    {
        $this->transporte = new \App\Transporte;
        $this->idiomaTransporte = new \App\IdiomaTransporte;
        $this->campos = [
            'imagem', 'icone', 'titulo', 'tipo', 'slug', 'cmsuser_id',
        ];
        $this->pathImagem = public_path().'/imagens/transportes';
        $this->sizesImagem = [
            'xs' => ['width' => 34, 'height' => 23],
            'sm' => ['width' => 50, 'height' => 34],
            'md' => ['width' => 100, 'height' => 68],
            'lg' => ['width' => 150, 'height' => 101]
        ];
        $this->pathIcone = public_path().'/imagens/transportes_icones';
        $this->sizesIcone = [
            'xs' => ['width' => 34, 'height' => 23],
            'sm' => ['width' => 50, 'height' => 34],
            'md' => ['width' => 100, 'height' => 68],
            'lg' => ['width' => 150, 'height' => 101]
        ];
        $this->widthOriginal = true;
    }

    function index()
    {

        $transportes = \App\Transporte::all();
        $idiomas = \App\Idioma::lists('titulo', 'sigla')->all();

        return view('cms::transporte.listar', ['transportes' => $transportes, 'idiomas' => $idiomas]);
    }

    public function listar(Request $request)
    {

        //Log::info('CAMPOS: '.$request->campos);

        //Auth::loginUsingId(2);

        $campos = explode(", ", $request->campos);

        /*$transportes = DB::table('transportes')
            ->select($campos)
            ->where([
                [$request->campoPesquisa, 'like', "%$request->dadoPesquisa%"],
            ])
            ->orderBy($request->ordem, $request->sentido)
            ->paginate($request->itensPorPagina);*/

        $transportes = DB::table('transportes')
        ->select($campos)
        ->join('idiomas_transportes', 'idiomas_transportes.transporte_id', '=', 'transportes.id')
        ->where([
            [$request->campoPesquisa, 'like', "%$request->dadoPesquisa%"],
            ['idiomas_transportes.idioma_sigla', 'pt_BR'],
        ])
        ->orderBy($request->ordem, $request->sentido)
        ->paginate($request->itensPorPagina);


        return $transportes;
    }

    public function inserir(Request $request)
    {

        $data = $request->all();

        //$data['transporte'] = [];

        $data['transporte'] += ['cmsuser_id' => auth()->guard('cms')->user()->id];//adiciona id do usuario
        $data['idioma'] += ['cmsuser_id' => auth()->guard('cms')->user()->id];//adiciona id do usuario

        //verifica se o index do campo existe no array e caso não exista inserir o campo com valor vazio.
        foreach($this->campos as $campo){
            if(!array_key_exists($campo, $data)){
                $data['transporte'] += [$campo => ''];
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
                $data['transporte']['imagem'] = $filename;
                $data['transporte']['icone'] = $filenameIcone;
                $inserir = $this->transporte->create($data['transporte']);

                $data['idioma']['transporte_id'] = $inserir->id;
                $inserir2 = $this->idiomaTransporte->create($data['idioma']);

                return $inserir;
            }else{
                return "erro";
            }
        }

        //return $this->transporte->create($data['transporte']);

        $inserir = $this->transporte->create($data['transporte']);
        $data['idioma']['transporte_id'] = $inserir->id;
        $inserir2 = $this->idiomaTransporte->create($data['idioma']);
        return $inserir;

    }

    public function detalhar($id)
    {
        $transporte = $this->transporte->where([
            ['id', '=', $id],
        ])->firstOrFail();
        return view('cms::transporte.detalhar', ['transporte' => $transporte]);
    }

    public function alterar(Request $request, $id)
    {

        $data = $request->all();
        //$data['transporte'] = [];
        $data['transporte'] += ['cmsuser_id' => auth()->guard('cms')->user()->id];//adiciona id do usuario

        //verifica se o index do campo existe no array e caso não exista inserir o campo com valor vazio.
        foreach($this->campos as $campo){
            if(!array_key_exists($campo, $data)){
                if($campo!='imagem' && $campo!='icone'){
                    $data['transporte'] += [$campo => ''];
                }
            }
        }
        $transporte = $this->transporte->where([
            ['id', '=', $id],
        ])->firstOrFail();

        $file = $request->file('file');
        $fileIcone = $request->file('fileIcone');

        /*if($file!=null){
            $filename = rand(1000,9999)."-".clean($file->getClientOriginalName());
            $filenameIcone = rand(1000,9999)."-".clean($fileIcone->getClientOriginalName());

            $imagemCms = new ImagemCms();
            $success = $imagemCms->alterar($file, $this->pathImagem, $filename, $this->sizesImagem, $this->widthOriginal, $transporte);
            $success = $imagemCms->alterar($fileIcone, $this->pathImagemIcone, $filenameIcone, $this->sizesImagemIcone, $this->widthOriginal, $transporte);

            if($success){
                $data['transporte']['imagem'] = $filename;
                $data['transporte']['icone'] = $filenameIcone;
                $transporte->update($data['transporte']);
                return $transporte->imagem;
            }else{
                return "erro";
            }
        }*/




        $successFile = true;
        if($file!=null){
            $filename = rand(1000,9999)."-".clean($file->getClientOriginalName());
            $imagemCms = new ImagemCms();
            $successFile = $imagemCms->alterar($file, $this->pathImagem, $filename, $this->sizesImagem, $this->widthOriginal, $transporte);
            if($successFile){
                $data['transporte']['imagem'] = $filename;
            }
        }
        $successFileIcone = true;
        if($fileIcone!=null){
            $filenameIcone = rand(1000,9999)."-".clean($fileIcone->getClientOriginalName());
            $imagemCms = new ImagemCms();
            $successFileIcone = $imagemCms->alterar($fileIcone, $this->pathIcone, $filenameIcone, $this->sizesIcone, $this->widthOriginal, $transporte);
            if($successFileIcone){
                $data['transporte']['icone'] = $filenameIcone;
            }
        }

        //remover imagem
        if($data['removerImagem']){
            $data['transporte']['imagem'] = '';
            if(file_exists($this->pathImagem."/".$transporte->imagem)) {
                unlink($this->pathImagem . "/" . $transporte->imagem);
            }
        }
        //remover icone
        if($data['removerIcone']){
            $data['transporte']['icone'] = '';
            if(file_exists($this->pathIcone."/".$transporte->icone)) {
                unlink($this->pathIcone . "/" . $transporte->icone);
            }
        }

        //$transporte->update($data['transporte']);
        //return "Gravado com sucesso";

        if($successFile && $successFileIcone){
            $transporte->update($data['transporte']);
            return $transporte->imagem;
        }else{
            return "erro";
        }
    }

    public function excluir($id)
    {
        //Auth::loginUsingId(2);

        $transporte = $this->transporte->where([
            ['id', '=', $id],
        ])->firstOrFail();

        //remover imagens        
        if(!empty($transporte->imagem)){
            //remover imagens
            $imagemCms = new ImagemCms();
            $imagemCms->excluir($this->pathImagem, $this->sizesImagem, $transporte);
        }
                

        $transporte->delete();

    }

    


}
