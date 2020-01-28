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

class IdiomaTransporteController extends Controller
{
    
    

    public function __construct()
    {
        $this->idiomaTransporte = new \App\IdiomaTransporte;
        $this->campos = [
            'imagem', 'titulo', 'idioma_sigla',
        ];
        $this->pathImagem = public_path().'/imagens/idiomas_transportes';
        $this->sizesImagem = [
            'xs' => ['width' => 140, 'height' => 79],
            'sm' => ['width' => 480, 'height' => 270],
            'md' => ['width' => 580, 'height' => 326],
            'lg' => ['width' => 1170, 'height' => 658]
        ];
        $this->widthOriginal = true;
    }

    function index($transporte_id)
    {
        $transporte = \App\Transporte::where('id', $transporte_id)->first();
        $idiomas = \App\Idioma::lists('titulo', 'sigla')->all();

        return view('cms::idiomas_transportes.listar', ['transporte_id' => $transporte->id, 'idiomas' => $idiomas]);
    }

    public function listar(Request $request)
    {

        $campos = explode(", ", $request->campos);

        $transportes = DB::table('idiomas_transportes')
            ->select($campos)
            ->where([
                [$request->campoPesquisa, 'ilike', "%$request->dadoPesquisa%"],
            ])
            ->where('transporte_id', $request->transporte_id)
            ->orderBy($request->ordem, $request->sentido)
            ->paginate($request->itensPorPagina);
        return $transportes;
    }

    public function inserir(Request $request)
    {

        $data = $request->all();

        $data['transporte'] += ['cmsuser_id' => auth()->guard('cms')->user()->id];//adiciona id do usuario

        /*if(empty($data['transporte']['transporte_id'])){
            $data['transporte']['transporte_id'] = 0;
        }*/

        //$data['transporte']['tipo_valores'] = 0;

        //verifica se o index do campo existe no array e caso não exista inserir o campo com valor vazio.
        foreach($this->campos as $campo){
            if(!array_key_exists($campo, $data)){
                $data['transporte'] += [$campo => ''];
            }
        }

        $file = $request->file('file');

        if($file!=null){
            $filename = rand(1000,9999)."-".clean($file->getClientOriginalName());
            $imagemCms = new ImagemCms();
            $success = $imagemCms->inserir($file, $this->pathImagem, $filename, $this->sizesImagem, $this->widthOriginal);
            
            if($success){
                $data['transporte']['imagem'] = $filename;
                return $this->idiomaTransporte->create($data['transporte']);
            }else{
                return "erro";
            }
        }

        $transporte = $this->idiomaTransporte->create($data['transporte']);

        return $transporte;

    }

    public function detalhar($id)
    {
        $idioma_transporte = $this->idiomaTransporte
            ->where([
            ['id', '=', $id],
        ])->first();
        $idiomas = \App\Idioma::lists('titulo', 'sigla')->all();

        $transporte_id = $idioma_transporte->transporte_id;

        return view('cms::idiomas_transportes.detalhar', [
            'idioma_transporte' => $idioma_transporte,
            'idiomas' => $idiomas,
            'transporte_id' => $transporte_id
        ]);
    }

    public function alterar(Request $request, $id)
    {
        $data = $request->all();
        $data['transporte'] += ['cmsuser_id' => auth()->guard('cms')->user()->id];//adiciona id do usuario

        //verifica se o index do campo existe no array e caso não exista inserir o campo com valor vazio.
        foreach($this->campos as $campo){
            if(!array_key_exists($campo, $data)){
                if($campo!='imagem'){
                    $data['transporte'] += [$campo => ''];
                }
            }
        }

	    $data['transporte']['tipo_valores'] = 0;

        $transporte = $this->idiomaTransporte->where([
            ['id', '=', $id],
        ])->firstOrFail();

        $file = $request->file('file');

        if($file!=null){
            $filename = rand(1000,9999)."-".clean($file->getClientOriginalName());
            $imagemCms = new ImagemCms();
            $success = $imagemCms->alterar($file, $this->pathImagem, $filename, $this->sizesImagem, $this->widthOriginal, $transporte);
            if($success){
                $data['transporte']['imagem'] = $filename;
                $transporte->update($data['transporte']);
                return $transporte->imagem;
            }else{
                return "erro";
            }
        }

        //remover imagem
        if($data['removerImagem']){
            $data['transporte']['imagem'] = '';
            if(file_exists($this->pathImagem."/".$transporte->imagem)) {
                unlink($this->pathImagem . "/" . $transporte->imagem);
            }
        }

        $transporte->update($data['transporte']);
        return "Gravado com sucesso";
    }

    public function excluir($id)
    {
        //Auth::loginUsingId(2);

        $transporte = $this->idiomaTransporte->where([
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
