<?php

namespace Cms\Controllers;

use Cms\Models\ImagemCms;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Facades\Image;

class ValorFiltroController extends Controller
{
    
    

    public function __construct()
    {
        $this->valor = new \App\ValorFiltro();
        $this->campos = [
            'imagem', 'titulo', 'filtro_id', 'cmsuser_id',
        ];
        $this->pathImagem = public_path().'/imagens/valores-filtro';
        $this->sizesImagem = [
            'xs' => ['width' => 140, 'height' => 79],
            'sm' => ['width' => 480, 'height' => 270],
            'md' => ['width' => 580, 'height' => 326],
            'lg' => ['width' => 1170, 'height' => 658]
        ];
        $this->widthOriginal = true;

        $this->pathArquivo = public_path().'/arquivos/valores-filtro';
    }

    function index($filtro_id)
    {


        $valores = \App\ValorFiltro::all();
        //$idiomas = \App\Idioma::lists('titulo', 'id')->all();

        return view('cms::valor_filtro.listar', ['valores' => $valores, 'filtro_id' => $filtro_id]);
        //return view('cms::valor_filtro.listar', ['valores' => $valores, 'modulo_id' => $modulo_id, 'idiomas' => $idiomas]);
    }

    public function listar(Request $request)
    {

        $campos = explode(", ", $request->campos);

        $valores = DB::table('valores_filtros')
            ->select($campos)
            ->where([
                [$request->campoPesquisa, 'ilike', "%$request->dadoPesquisa%"],
                ['filtro_id', $request->filtro_id],
            ])
            ->orderBy($request->ordem, $request->sentido)
            ->paginate($request->itensPorPagina);
        return ['valores' => $valores, 'tipos' => config('constants.TIPOS')];
    }


    public function inserir(Request $request)
    {

        $data = $request->all();

        $data['valor'] += ['cmsuser_id' => auth()->guard('cms')->user()->id];//adiciona id do usuario

        //verifica se o index do campo existe no array e caso não exista inserir o campo com valor vazio.
        foreach($this->campos as $campo){
            if(!array_key_exists($campo, $data)){
                $data['valor'] += [$campo => ''];
            }
        }

        $file = $request->file('file');
        $arquivo = $request->file('arquivo');


        $successFile = true;
        if($file!=null){
            $filename = rand(1000,9999)."-".clean($file->getClientOriginalName());
            $imagemCms = new ImagemCms();
            $successFile = $imagemCms->inserir($file, $this->pathImagem, $filename, $this->sizesImagem, $this->widthOriginal);
            if($successFile){
                $data['valor']['imagem'] = $filename;
            }
        }

        $successArquivo = true;
        if($arquivo!=null){
            $filenameArquivo = rand(1000,9999)."-".clean($arquivo->getClientOriginalName());
            $successArquivo = $arquivo->move($this->pathArquivo, $filenameArquivo);
            if($successArquivo){
                $data['valor']['arquivo'] = $filenameArquivo;
            }
        }


        if($successFile && $successArquivo){
            return $this->valor->create($data['valor']);
        }else{
            return "erro";
        }


        return $this->valor->create($data['valor']);

    }

    public function detalhar($id)
    {

        $valor = $this->valor->where([
            ['id', '=', $id],
        ])->firstOrFail();


        $filtro_id = $valor->filtro_id;

        return view('cms::valor_filtro.detalhar', ['valor' => $valor, 'filtro_id' => $filtro_id]);

    }



    public function alterar(Request $request, $id)
    {
        $data = $request->all();

        //return $data;

        $data['valor'] += ['cmsuser_id' => auth()->guard('cms')->user()->id];//adiciona id do usuario

        //verifica se o index do campo existe no array e caso não exista inserir o campo com valor vazio.
        foreach($this->campos as $campo){
            if(!array_key_exists($campo, $data)){
                if($campo!='imagem' && $campo!='arquivo'){
                    $data['valor'] += [$campo => ''];
                }
            }
        }
        $valor = $this->valor->where([
            ['id', '=', $id],
        ])->firstOrFail();


        $file = $request->file('file');
        $arquivo = $request->file('arquivo');

        //remover imagem
        if($data['removerImagem']){
            $data['valor']['imagem'] = '';
            if(file_exists($this->pathImagem."/".$valor->imagem)) {
                unlink($this->pathImagem . "/" . $valor->imagem);
            }
        }


        if($data['removerArquivo']){
            $data['valor']['arquivo'] = '';
            if(file_exists($this->pathArquivo."/".$valor->arquivo)) {
                unlink($this->pathArquivo . "/" . $valor->arquivo);
            }
        }


        $successFile = true;
        if($file!=null){
            $filename = rand(1000,9999)."-".clean($file->getClientOriginalName());
            $imagemCms = new ImagemCms();
            $successFile = $imagemCms->alterar($file, $this->pathImagem, $filename, $this->sizesImagem, $this->widthOriginal, $valor);
            if($successFile){
                $data['valor']['imagem'] = $filename;
            }
        }

        $successArquivo = true;
        if($arquivo!=null){
            $filenameArquivo = rand(1000,9999)."-".clean($arquivo->getClientOriginalName());
            $successArquivo = $arquivo->move($this->pathArquivo, $filenameArquivo);
            if($successArquivo){
                $data['valor']['arquivo'] = $filenameArquivo;
            }
        }

        if($successFile && $successArquivo){

            $valor->update($data['valor']);
            return $valor->imagem;
        }else{
            return "erro";
        }

        //$valor->update($data['valor']);
        //return "Gravado com sucesso";
    }

    public function excluir($id)
    {
        //Auth::loginUsingId(2);

        $valor = $this->valor->where([
            ['id', '=', $id],
        ])->firstOrFail();

        //remover imagens        
        if(!empty($valor->imagem)){
            //remover imagens
            $imagemCms = new ImagemCms();
            $imagemCms->excluir($this->pathImagem, $this->sizesImagem, $valor);
        }


        if(!empty($valor->arquivo)) {
            if (file_exists($this->pathArquivo . "/" . $valor->arquivo)) {
                unlink($this->pathArquivo . "/" . $valor->arquivo);
            }
        }

        $valor->delete();

    }


    public function status($id)
    {
        $tipo_atual = DB::table('valores_filtros')->where('id', $id)->first();
        $status = $tipo_atual->status == 0 ? 1 : 0;
        DB::table('valores_filtros')->where('id', $id)->update(['status' => $status]);

    }

}
