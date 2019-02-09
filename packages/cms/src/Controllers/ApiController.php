<?php

namespace Cms\Controllers;

use Cms\Models\ImagemCms;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Facades\Image;

class ApiController extends Controller
{
    
    

    public function __construct()
    {
        $this->api = new \App\Api;
        $this->idiomaApi = new \App\IdiomaApi;
        $this->campos = [
            'versao', 'tipo', 'url', 'resposta', 'cmsuser_id',
        ];
        $this->pathImagem = public_path().'/imagens/apis';
        $this->sizesImagem = [
            'xs' => ['width' => 34, 'height' => 23],
            'sm' => ['width' => 50, 'height' => 34],
            'md' => ['width' => 100, 'height' => 68],
            'lg' => ['width' => 150, 'height' => 101]
        ];
        $this->widthOriginal = true;
    }

    function index()
    {

        $apis = \App\Api::all();
        $idiomas = \App\Idioma::lists('titulo', 'sigla')->all();

        return view('cms::api.listar', ['apis' => $apis, 'idiomas' => $idiomas]);
    }

    public function listar(Request $request)
    {

        //Log::info('CAMPOS: '.$request->campos);

        //Auth::loginUsingId(2);

        $campos = explode(", ", $request->campos);

        /*$apis = DB::table('apis')
            ->select($campos)
            ->where([
                [$request->campoPesquisa, 'like', "%$request->dadoPesquisa%"],
            ])
            ->orderBy($request->ordem, $request->sentido)
            ->paginate($request->itensPorPagina);*/

        $apis = DB::table('apis')
        ->select($campos)
        ->join('idiomas_apis', 'idiomas_apis.api_id', '=', 'apis.id')
        ->where([
            [$request->campoPesquisa, 'like', "%$request->dadoPesquisa%"],
            ['idiomas_apis.idioma_sigla', 'pt_BR'],
        ])
        ->orderBy($request->ordem, $request->sentido)
        ->paginate($request->itensPorPagina);


        return $apis;
    }

    public function inserir(Request $request)
    {

        $data = $request->all();

        $data['indicador'] = [];

        $data['api'] += ['cmsuser_id' => auth()->guard('cms')->user()->id];//adiciona id do usuario
        $data['idioma'] += ['cmsuser_id' => auth()->guard('cms')->user()->id];//adiciona id do usuario

        //verifica se o index do campo existe no array e caso não exista inserir o campo com valor vazio.
        foreach($this->campos as $campo){
            if(!array_key_exists($campo, $data)){
                $data['api'] += [$campo => ''];
            }
        }

        $file = $request->file('file');

        if($file!=null){
            $filename = rand(1000,9999)."-".clean($file->getClientOriginalName());
            $imagemCms = new ImagemCms();
            $success = $imagemCms->inserir($file, $this->pathImagem, $filename, $this->sizesImagem, $this->widthOriginal);
            
            if($success){
                $data['api']['imagem'] = $filename;
                $inserir = $this->api->create($data['api']);

                $data['idioma']['api_id'] = $inserir->id;
                $inserir2 = $this->idiomaApi->create($data['idioma']);

                return $inserir;
            }else{
                return "erro";
            }
        }

        //return $this->api->create($data['api']);

        $inserir = $this->api->create($data['api']);
        $data['idioma']['api_id'] = $inserir->id;
        $inserir2 = $this->idiomaApi->create($data['idioma']);
        return $inserir;

    }

    public function detalhar($id)
    {
        $api = $this->api->where([
            ['id', '=', $id],
        ])->firstOrFail();
        return view('cms::api.detalhar', ['api' => $api]);
    }

    public function alterar(Request $request, $id)
    {
        $data = $request->all();
        $data['api'] += ['cmsuser_id' => auth()->guard('cms')->user()->id];//adiciona id do usuario

        //verifica se o index do campo existe no array e caso não exista inserir o campo com valor vazio.
        foreach($this->campos as $campo){
            if(!array_key_exists($campo, $data)){
                if($campo!='imagem'){
                    $data['api'] += [$campo => ''];
                }
            }
        }
        $api = $this->api->where([
            ['id', '=', $id],
        ])->firstOrFail();

        $file = $request->file('file');

        if($file!=null){
            $filename = rand(1000,9999)."-".clean($file->getClientOriginalName());
            $imagemCms = new ImagemCms();
            $success = $imagemCms->alterar($file, $this->pathImagem, $filename, $this->sizesImagem, $this->widthOriginal, $api);
            if($success){
                $data['api']['imagem'] = $filename;
                $api->update($data['api']);
                return $api->imagem;
            }else{
                return "erro";
            }
        }

        //remover imagem
        if($data['removerImagem']){
            $data['api']['imagem'] = '';
            if(file_exists($this->pathImagem."/".$api->imagem)) {
                unlink($this->pathImagem . "/" . $api->imagem);
            }
        }

        $api->update($data['api']);
        return "Gravado com sucesso";
    }

    public function excluir($id)
    {
        //Auth::loginUsingId(2);

        $api = $this->api->where([
            ['id', '=', $id],
        ])->firstOrFail();

        //remover imagens        
        if(!empty($api->imagem)){
            //remover imagens
            $imagemCms = new ImagemCms();
            $imagemCms->excluir($this->pathImagem, $this->sizesImagem, $api);
        }
                

        $api->delete();

    }

    


}
