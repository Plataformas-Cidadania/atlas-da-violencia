<?php

namespace Cms\Controllers;

use Cms\Models\ImagemCms;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Facades\Image;

class FaviconController extends Controller
{
    
    

    public function __construct()
    {
        $this->favicon = new \App\Favicon;
        $this->campos = [
            'imagem', 'titulo', 'cmsuser_id',
        ];
        $this->pathImagem = public_path().'/imagens/favicons';
        $this->sizesImagem = [
            '16x16' => ['width' => 16, 'height' => 16],
            '32x32' => ['width' => 32, 'height' => 32],
            '48x48' => ['width' => 48, 'height' => 48],
            '64x64' => ['width' => 64, 'height' => 64],
            '72x72' => ['width' => 72, 'height' => 72],
            '96x96' => ['width' => 96, 'height' => 96],
            '114x114' => ['width' => 114, 'height' => 114],
            '128x128' => ['width' => 128, 'height' => 128],
            '144x144' => ['width' => 144, 'height' => 144],
            '256x256' => ['width' => 256, 'height' => 256],
            '512x512' => ['width' => 512, 'height' => 512],
        ];
        $this->widthOriginal = true;
    }

    function index()
    {

        $favicons = \App\Favicon::all();
        $temas = \App\Tema::where('tema_id', 0)->lists('tema', 'id')->all();
        $idiomas = \App\Idioma::lists('titulo', 'sigla')->all();

        return view('cms::favicon.listar', ['favicons' => $favicons, 'temas' => $temas, 'idiomas' => $idiomas]);
    }

    public function listar(Request $request)
    {

        //Log::info('CAMPOS: '.$request->campos);

        //Auth::loginUsingId(2);

        $campos = explode(", ", $request->campos);

        $favicons = DB::table('favicons')
            ->select($campos)
            ->where([
                [$request->campoPesquisa, 'like', "%$request->dadoPesquisa%"],
            ])
            ->orderBy($request->ordem, $request->sentido)
            ->paginate($request->itensPorPagina);
        return $favicons;
    }

    public function inserir(Request $request)
    {

        $data = $request->all();

        $data['favicon'] += ['cmsuser_id' => auth()->guard('cms')->user()->id];//adiciona id do usuario

        //verifica se o index do campo existe no array e caso não exista inserir o campo com valor vazio.
        foreach($this->campos as $campo){
            if(!array_key_exists($campo, $data)){
                $data['favicon'] += [$campo => ''];
            }
        }

        $file = $request->file('file');

        if($file!=null){
            $filename = rand(1000,9999)."-".clean($file->getClientOriginalName());
            $imagemCms = new ImagemCms();
            $success = $imagemCms->inserir($file, $this->pathImagem, $filename, $this->sizesImagem, $this->widthOriginal);
            
            if($success){
                $data['favicon']['imagem'] = $filename;
                return $this->favicon->create($data['favicon']);
            }else{
                return "erro";
            }
        }

        return $this->favicon->create($data['favicon']);

    }

    public function detalhar($id)
    {
        $favicon = $this->favicon->where([
            ['id', '=', $id],
        ])->firstOrFail();
        $temas = \App\Tema::where('tema_id', 0)->lists('tema', 'id')->all();
        $idiomas = \App\Idioma::lists('titulo', 'sigla')->all();

        return view('cms::favicon.detalhar', ['favicon' => $favicon, 'temas' => $temas, 'idiomas' => $idiomas]);
    }

    public function alterar(Request $request, $id)
    {
        $data = $request->all();
        $data['favicon'] += ['cmsuser_id' => auth()->guard('cms')->user()->id];//adiciona id do usuario

        //verifica se o index do campo existe no array e caso não exista inserir o campo com valor vazio.
        foreach($this->campos as $campo){
            if(!array_key_exists($campo, $data)){
                if($campo!='imagem'){
                    $data['favicon'] += [$campo => ''];
                }
            }
        }
        $favicon = $this->favicon->where([
            ['id', '=', $id],
        ])->firstOrFail();

        $file = $request->file('file');

        if($file!=null){
            $filename = rand(1000,9999)."-".clean($file->getClientOriginalName());
            $imagemCms = new ImagemCms();
            $success = $imagemCms->alterar($file, $this->pathImagem, $filename, $this->sizesImagem, $this->widthOriginal, $favicon);
            if($success){
                $data['favicon']['imagem'] = $filename;
                $favicon->update($data['favicon']);
                return $favicon->imagem;
            }else{
                return "erro";
            }
        }

        //remover imagem
        if($data['removerImagem']){
            $data['favicon']['imagem'] = '';
            if(file_exists($this->pathImagem."/".$favicon->imagem)) {
                unfavicon($this->pathImagem . "/" . $favicon->imagem);
            }
        }

        $favicon->update($data['favicon']);
        return "Gravado com sucesso";
    }

    public function excluir($id)
    {
        //Auth::loginUsingId(2);

        $favicon = $this->favicon->where([
            ['id', '=', $id],
        ])->firstOrFail();

        //remover imagens        
        if(!empty($favicon->imagem)){
            //remover imagens
            $imagemCms = new ImagemCms();
            $imagemCms->excluir($this->pathImagem, $this->sizesImagem, $favicon);
        }

        $favicon->delete();

    }

    


}
