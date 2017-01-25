<?php

namespace Cms\Controllers;

use Cms\Models\ImagemCms;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Facades\Image;

class IndiceController extends Controller
{
    
    

    public function __construct()
    {
        $this->indice = new \App\Indice;
        $this->campos = [
            'imagem', 'posicao', 'titulo', 'descricao', 'indice', 'tags', 'cmsuser_id',
        ];
        $this->pathImagem = public_path().'/imagens/indices';
        $this->sizesImagem = [
            'xs' => ['width' => 140, 'height' => 79],
            'sm' => ['width' => 480, 'height' => 270],
            'md' => ['width' => 580, 'height' => 326],
            'lg' => ['width' => 1170, 'height' => 658]
        ];
        $this->widthOriginal = true;
    }

    function index()
    {

        $indices = \App\Indice::all();

        return view('cms::indice.listar', ['indices' => $indices]);
    }

    public function listar(Request $request)
    {

        //Log::info('CAMPOS: '.$request->campos);

        //Auth::loginUsingId(2);

        $campos = explode(", ", $request->campos);

        $indices = DB::table('indices')
            ->select($campos)
            ->where([
                [$request->campoPesquisa, 'like', "%$request->dadoPesquisa%"],
            ])
            ->orderBy($request->ordem, $request->sentido)
            ->paginate($request->itensPorPagina);
        return $indices;
    }

    public function inserir(Request $request)
    {

        $data = $request->all();

        $data['indice'] += ['cmsuser_id' => auth()->guard('cms')->user()->id];//adiciona id do usuario

        //verifica se o index do campo existe no array e caso não exista inserir o campo com valor vazio.
        foreach($this->campos as $campo){
            if(!array_key_exists($campo, $data)){
                $data['indice'] += [$campo => ''];
            }
        }

        $file = $request->file('file');

        if($file!=null){
            $filename = rand(1000,9999)."-".clean($file->getClientOriginalName());
            $imagemCms = new ImagemCms();
            $success = $imagemCms->inserir($file, $this->pathImagem, $filename, $this->sizesImagem, $this->widthOriginal);
            
            if($success){
                $data['indice']['imagem'] = $filename;
                return $this->indice->create($data['indice']);
            }else{
                return "erro";
            }
        }

        return $this->indice->create($data['indice']);

    }

    public function detalhar($id)
    {
        $indice = $this->indice->where([
            ['id', '=', $id],
        ])->firstOrFail();
        return view('cms::indice.detalhar', ['indice' => $indice]);
    }

    public function alterar(Request $request, $id)
    {
        $data = $request->all();
        $data['indice'] += ['cmsuser_id' => auth()->guard('cms')->user()->id];//adiciona id do usuario

        //verifica se o index do campo existe no array e caso não exista inserir o campo com valor vazio.
        foreach($this->campos as $campo){
            if(!array_key_exists($campo, $data)){
                if($campo!='imagem'){
                    $data['indice'] += [$campo => ''];
                }
            }
        }
        $indice = $this->indice->where([
            ['id', '=', $id],
        ])->firstOrFail();

        $file = $request->file('file');

        if($file!=null){
            $filename = rand(1000,9999)."-".clean($file->getClientOriginalName());
            $imagemCms = new ImagemCms();
            $success = $imagemCms->alterar($file, $this->pathImagem, $filename, $this->sizesImagem, $this->widthOriginal, $indice);
            if($success){
                $data['indice']['imagem'] = $filename;
                $indice->update($data['indice']);
                return $indice->imagem;
            }else{
                return "erro";
            }
        }

        //remover imagem
        if($data['removerImagem']){
            $data['indice']['imagem'] = '';
            if(file_exists($this->pathImagem."/".$indice->imagem)) {
                unindice($this->pathImagem . "/" . $indice->imagem);
            }
        }

        $indice->update($data['indice']);
        return "Gravado com sucesso";
    }

    public function excluir($id)
    {
        //Auth::loginUsingId(2);

        $indice = $this->indice->where([
            ['id', '=', $id],
        ])->firstOrFail();

        //remover imagens        
        if(!empty($indice->imagem)){
            //remover imagens
            $imagemCms = new ImagemCms();
            $imagemCms->excluir($this->pathImagem, $this->sizesImagem, $indice);
        }

        $indice->delete();

    }

    


}
