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

class IdiomaApiController extends Controller
{
    
    

    public function __construct()
    {
        $this->idiomaApi = new \App\IdiomaApi;
        $this->campos = [
            'imagem', 'titulo', 'descricao', 'idioma_sigla',
        ];
        $this->pathImagem = public_path().'/imagens/idiomas_apis';
        $this->sizesImagem = [
            'xs' => ['width' => 140, 'height' => 79],
            'sm' => ['width' => 480, 'height' => 270],
            'md' => ['width' => 580, 'height' => 326],
            'lg' => ['width' => 1170, 'height' => 658]
        ];
        $this->widthOriginal = true;
    }

    function index($api_id)
    {
        $api = \App\Api::where('id', $api_id)->first();
        $idiomas = \App\Idioma::lists('titulo', 'sigla')->all();

        return view('cms::idiomas_apis.listar', ['api_id' => $api->id, 'idiomas' => $idiomas]);
    }

    public function listar(Request $request)
    {

        $campos = explode(", ", $request->campos);

        $apis = DB::table('idiomas_apis')
            ->select($campos)
            ->where([
                [$request->campoPesquisa, 'ilike', "%$request->dadoPesquisa%"],
            ])
            ->where('api_id', $request->api_id)
            ->orderBy($request->ordem, $request->sentido)
            ->paginate($request->itensPorPagina);
        return $apis;
    }

    public function inserir(Request $request)
    {

        $data = $request->all();

        $data['api'] += ['cmsuser_id' => auth()->guard('cms')->user()->id];//adiciona id do usuario

        /*if(empty($data['api']['api_id'])){
            $data['api']['api_id'] = 0;
        }*/

        //$data['api']['tipo_valores'] = 0;

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
                return $this->idiomaApi->create($data['api']);
            }else{
                return "erro";
            }
        }

        $api = $this->idiomaApi->create($data['api']);

        return $api;

    }

    public function detalhar($id)
    {
        $idioma_api = $this->idiomaApi
            ->where([
            ['id', '=', $id],
        ])->first();
        $idiomas = \App\Idioma::lists('titulo', 'sigla')->all();

        $api_id = $idioma_api->api_id;

        return view('cms::idiomas_apis.detalhar', [
            'idioma_api' => $idioma_api,
            'idiomas' => $idiomas,
            'api_id' => $api_id
        ]);
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

	    $data['api']['tipo_valores'] = 0;

        $api = $this->idiomaApi->where([
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

        $api = $this->idiomaApi->where([
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
