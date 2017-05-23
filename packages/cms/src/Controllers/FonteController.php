<?php

namespace Cms\Controllers;

use Cms\Models\ImagemCms;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Facades\Image;

class FonteController extends Controller
{
    
    

    public function __construct()
    {
        $this->fonte = new \App\Fonte;
        $this->campos = [
            'titulo', 'cmsuser_id',
        ];
        $this->pathImagem = public_path().'/imagens/fontes';
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

        $fontes = \App\Fonte::all();

        return view('cms::fonte.listar', ['fontes' => $fontes]);
    }

    public function listar(Request $request)
    {

        //Log::info('CAMPOS: '.$request->campos);

        //Auth::loginUsingId(2);

        $campos = explode(", ", $request->campos);

        $fontes = DB::table('fontes')
            ->select($campos)
            ->where([
                [$request->campoPesquisa, 'like', "%$request->dadoPesquisa%"],
            ])
            ->orderBy($request->ordem, $request->sentido)
            ->paginate($request->itensPorPagina);
        return $fontes;
    }

    public function inserir(Request $request)
    {

        $data = $request->all();

        $data['fonte'] += ['cmsuser_id' => auth()->guard('cms')->user()->id];//adiciona id do usuario

        //verifica se o index do campo existe no array e caso não exista inserir o campo com valor vazio.
        foreach($this->campos as $campo){
            if(!array_key_exists($campo, $data)){
                $data['fonte'] += [$campo => ''];
            }
        }

        $file = $request->file('file');

        if($file!=null){
            $filename = rand(1000,9999)."-".clean($file->getClientOriginalName());
            $imagemCms = new ImagemCms();
            $success = $imagemCms->inserir($file, $this->pathImagem, $filename, $this->sizesImagem, $this->widthOriginal);
            
            if($success){
                $data['fonte']['imagem'] = $filename;
                return $this->fonte->create($data['fonte']);
            }else{
                return "erro";
            }
        }

        return $this->fonte->create($data['fonte']);

    }

    public function detalhar($id)
    {
        $fonte = $this->fonte->where([
            ['id', '=', $id],
        ])->firstOrFail();
        return view('cms::fonte.detalhar', ['fonte' => $fonte]);
    }

    public function alterar(Request $request, $id)
    {
        $data = $request->all();
        $data['fonte'] += ['cmsuser_id' => auth()->guard('cms')->user()->id];//adiciona id do usuario

        //verifica se o index do campo existe no array e caso não exista inserir o campo com valor vazio.
        foreach($this->campos as $campo){
            if(!array_key_exists($campo, $data)){
                if($campo!='imagem'){
                    $data['fonte'] += [$campo => ''];
                }
            }
        }
        $fonte = $this->fonte->where([
            ['id', '=', $id],
        ])->firstOrFail();

        $file = $request->file('file');

        if($file!=null){
            $filename = rand(1000,9999)."-".clean($file->getClientOriginalName());
            $imagemCms = new ImagemCms();
            $success = $imagemCms->alterar($file, $this->pathImagem, $filename, $this->sizesImagem, $this->widthOriginal, $fonte);
            if($success){
                $data['fonte']['imagem'] = $filename;
                $fonte->update($data['fonte']);
                return $fonte->imagem;
            }else{
                return "erro";
            }
        }

        //remover imagem
        if($data['removerImagem']){
            $data['fonte']['imagem'] = '';
            if(file_exists($this->pathImagem."/".$fonte->imagem)) {
                unlink($this->pathImagem . "/" . $fonte->imagem);
            }
        }

        $fonte->update($data['fonte']);
        return "Gravado com sucesso";
    }

    public function excluir($id)
    {
        //Auth::loginUsingId(2);

        $fonte = $this->fonte->where([
            ['id', '=', $id],
        ])->firstOrFail();

        //remover imagens        
        if(!empty($fonte->imagem)){
            //remover imagens
            $imagemCms = new ImagemCms();
            $imagemCms->excluir($this->pathImagem, $this->sizesImagem, $fonte);
        }
                

        $fonte->delete();

    }

    


}
