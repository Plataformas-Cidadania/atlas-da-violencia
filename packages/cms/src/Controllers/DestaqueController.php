<?php

namespace Cms\Controllers;

use Cms\Models\ImagemCms;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Facades\Image;

class DestaqueController extends Controller
{



    public function __construct()
    {
        $this->destaque = new \App\Destaque;
        $this->campos = [
            'imagem', 'titulo', 'chamada', 'link', 'cmsuser_id', 'idioma_sigla',
        ];
        $this->pathImagem = public_path().'/imagens/destaques';
        $this->sizesImagem = [
            'xs' => ['width' => 200, 'height' => 51],
            'sm' => ['width' => 480, 'height' => 122],
            'md' => ['width' => 780, 'height' => 198],
            'lg' => ['width' => 1180, 'height' => 300]
        ];
        $this->widthOriginal = true;
    }

    function index()
    {

        $destaques = \App\Destaque::all();
        $idiomas = \App\Idioma::lists('titulo', 'sigla')->all();

        return view('cms::destaque.listar', ['destaques' => $destaques, 'idiomas' => $idiomas]);
    }

    public function listar(Request $request)
    {

        //Log::info('CAMPOS: '.$request->campos);

        //Auth::loginUsingId(2);

        $campos = explode(", ", $request->campos);

        $destaques = DB::table('destaques')
            ->select($campos)
            ->where([
                [$request->campoPesquisa, 'like', "%$request->dadoPesquisa%"],
            ])
            ->orderBy($request->ordem, $request->sentido)
            ->paginate($request->itensPorPagina);
        return $destaques;
    }

    public function inserir(Request $request)
    {

        $data = $request->all();

        $data['destaque'] += ['cmsuser_id' => auth()->guard('cms')->user()->id];//adiciona id do usuario

        //verifica se o index do campo existe no array e caso não exista inserir o campo com valor vazio.
        foreach($this->campos as $campo){
            if(!array_key_exists($campo, $data)){
                $data['destaque'] += [$campo => ''];
            }
        }

        $file = $request->file('file');

        if($file!=null){
            $filename = rand(1000,9999)."-".clean($file->getClientOriginalName());
            $imagemCms = new ImagemCms();
            $success = $imagemCms->inserir($file, $this->pathImagem, $filename, $this->sizesImagem, $this->widthOriginal);

            if($success){
                $data['destaque']['imagem'] = $filename;
                return $this->destaque->create($data['destaque']);
            }else{
                return "erro";
            }
        }

        return $this->destaque->create($data['destaque']);

    }

    public function detalhar($id)
    {
        $destaque = $this->destaque->where([
            ['id', '=', $id],
        ])->firstOrFail();
        $idiomas = \App\Idioma::lists('titulo', 'sigla')->all();

        return view('cms::destaque.detalhar', ['destaque' => $destaque, 'idiomas' => $idiomas]);
    }

    public function alterar(Request $request, $id)
    {
        $data = $request->all();
        $data['destaque'] += ['cmsuser_id' => auth()->guard('cms')->user()->id];//adiciona id do usuario

        //verifica se o index do campo existe no array e caso não exista inserir o campo com valor vazio.
        foreach($this->campos as $campo){
            if(!array_key_exists($campo, $data)){
                if($campo!='imagem'){
                    $data['destaque'] += [$campo => ''];
                }
            }
        }
        $destaque = $this->destaque->where([
            ['id', '=', $id],
        ])->firstOrFail();

        $file = $request->file('file');

        if($file!=null){
            $filename = rand(1000,9999)."-".clean($file->getClientOriginalName());
            $imagemCms = new ImagemCms();
            $success = $imagemCms->alterar($file, $this->pathImagem, $filename, $this->sizesImagem, $this->widthOriginal, $destaque);
            if($success){
                $data['destaque']['imagem'] = $filename;
                $destaque->update($data['destaque']);
                return $destaque->imagem;
            }else{
                return "erro";
            }
        }

        //remover imagem
        if($data['removerImagem']){
            $data['destaque']['imagem'] = '';
            if(file_exists($this->pathImagem."/".$destaque->imagem)) {
                unlink($this->pathImagem . "/" . $destaque->imagem);
            }
        }

        $destaque->update($data['destaque']);
        return "Gravado com sucesso";
    }

    public function excluir($id)
    {
        //Auth::loginUsingId(2);

        $destaque = $this->destaque->where([
            ['id', '=', $id],
        ])->firstOrFail();

        //remover imagens
        if(!empty($destaque->imagem)){
            //remover imagens
            $imagemCms = new ImagemCms();
            $imagemCms->excluir($this->pathImagem, $this->sizesImagem, $destaque);
        }


        $destaque->delete();

    }




}
