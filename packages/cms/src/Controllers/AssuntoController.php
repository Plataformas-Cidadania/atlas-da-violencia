<?php

namespace Cms\Controllers;

use Cms\Models\ImagemCms;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Facades\Image;
//use function MongoDB\BSON\toJSON;

class AssuntoController extends Controller
{

    public function __construct()
    {
        $this->assunto = new \App\Assunto;
        $this->idiomaAssunto = new \App\IdiomaAssunto;
        $this->campos = [
              'assunto', 'tipo','cmsuser_id',
        ];
        $this->pathImagem = public_path().'/imagens/assuntos';
        $this->sizesImagem = [
            'xs' => ['width' => 32, 'height' => 32],
            'sm' => ['width' => 64, 'height' => 64],
            'md' => ['width' => 128, 'height' => 128],
            'lg' => ['width' => 256, 'height' => 256]
        ];
        $this->widthOriginal = true;
    }

    function index()    {

        $idiomas = \App\Idioma::lists('titulo', 'sigla')->all();

        return view('cms::assunto.listar', ['idiomas' => $idiomas]);

        //$assuntos = [];
        //$assuntos = $this->mountListAssuntos($assuntos, 0, "");

        //return view('cms::assunto.listar', ['assuntos' => $assuntos]);

        //$assuntos = $this->mountArrayAssuntos(0);
        //return view('cms::teste-array', ['assuntos' => $assuntos]);

    }

    public function listar(Request $request)
    {

        //Log::info('CAMPOS: '.$request->campos);

        //Auth::loginUsingId(2);

        $campos = explode(", ", $request->campos);

        $assuntos = DB::table('assuntos')
            ->select($campos)
            ->join('idiomas_assuntos', 'idiomas_assuntos.assunto_id', '=', 'assuntos.id')
            ->where([
                [$request->campoPesquisa, 'ilike', "%$request->dadoPesquisa%"],
		        ['idiomas_assuntos.idioma_sigla', 'pt_BR'],
            ])
            ->orderBy($request->ordem, $request->sentido)
            ->paginate($request->itensPorPagina);
        return $assuntos;
    }

    public function inserir(Request $request)
    {

        $data = $request->all();

        $data['assunto'] += ['cmsuser_id' => auth()->guard('cms')->user()->id];//adiciona id do usuario
        $data['idioma'] += ['cmsuser_id' => auth()->guard('cms')->user()->id];//adiciona id do usuario

        //verifica se o index do campo existe no array e caso não exista inserir o campo com valor vazio.
        foreach($this->campos as $campo){
            if(!array_key_exists($campo, $data['assunto'])){
                $data['assunto'] += [$campo => ''];
            }
        }
        //$data['assunto']['assunto'] = '';


        $file = $request->file('file');


        if($file!=null){
            $filename = rand(1000,9999)."-".clean($file->getClientOriginalName());
            $imagemCms = new ImagemCms();
            $success = $imagemCms->inserir($file, $this->pathImagem, $filename, $this->sizesImagem, $this->widthOriginal);
            
            if($success){
                $data['assunto']['imagem'] = $filename;                
		        $inserir = $this->assunto->create($data['assunto']);
	            $data['idioma']['assunto_id'] = $inserir->id;
       		    $inserir2 = $this->idiomaAssunto->create($data['idioma']);
	            return $inserir;

            }else{
                return "erro";
            }
        }

        $inserir = $this->assunto->create($data['assunto']);
        $data['idioma']['assunto_id'] = $inserir->id;
        Log::info([$data['idioma']]);
        $inserir2 = $this->idiomaAssunto->create($data['idioma']);
        return $inserir;

    }

    public function detalhar($id)
    {

        //$assuntos = \App\Assunto::lists('assunto', 'id')->all();
        //$assuntos = [];
        //$assuntos = $this->mountListAssuntos($assuntos, 0, "");

        $assunto = $this->assunto->where([
            ['id', '=', $id],
        ])->firstOrFail();

        $assunto_id = $assunto->assunto_id;

        return view('cms::assunto.detalhar', ['assunto' => $assunto]);
    }

    public function alterar(Request $request, $id)
    {
        $data = $request->all();
        $data['assunto'] += ['cmsuser_id' => auth()->guard('cms')->user()->id];//adiciona id do usuario

        //verifica se o index do campo existe no array e caso não exista inserir o campo com valor vazio.
        foreach($this->campos as $campo){
            if(!array_key_exists($campo, $data)){
                if($campo!='imagem'){
                    $data['assunto'] += [$campo => ''];
                }
            }
        }

        $assunto = $this->assunto->where([
            ['id', '=', $id],
        ])->firstOrFail();

        $file = $request->file('file');

        if($file!=null){
            $filename = rand(1000,9999)."-".clean($file->getClientOriginalName());
            $imagemCms = new ImagemCms();
            $success = $imagemCms->alterar($file, $this->pathImagem, $filename, $this->sizesImagem, $this->widthOriginal, $assunto);
            if($success){
                $data['assunto']['imagem'] = $filename;
                $assunto->update($data['assunto']);
                return $assunto->imagem;
            }else{
                return "erro";
            }
        }

        //remover imagem
        if($data['removerImagem']){
            $data['assunto']['imagem'] = '';
            if(file_exists($this->pathImagem."/".$assunto->imagem)) {
                unlink($this->pathImagem . "/" . $assunto->imagem);
            }
        }

        $assunto->update($data['assunto']);
        return "Gravado com sucesso";
    }

    public function excluir($id)
    {
        //Auth::loginUsingId(2);

        $assunto = $this->assunto->where([
            ['id', '=', $id],
        ])->firstOrFail();

        //remover imagens        
        if(!empty($assunto->imagem)){
            //remover imagens
            $imagemCms = new ImagemCms();
            $imagemCms->excluir($this->pathImagem, $this->sizesImagem, $assunto);
        }
                

        $assunto->delete();

    }

    public function status($id)
    {
        $tipo_atual = DB::table('assuntos')->where('id', $id)->first();
        $status = $tipo_atual->status == 0 ? 1 : 0;
        DB::table('assuntos')->where('id', $id)->update(['status' => $status]);

    }

    public function positionUp($id)
    {

        $posicao_atual = DB::table('assuntos')->where('id', $id)->first();
        $upPosicao = $posicao_atual->position-1;
        $posicao = $posicao_atual->position;

        //Coloca com a posicao do anterior
        DB::table('assuntos')->where('position', $upPosicao)->update(['position' => $posicao]);

        //atualiza a posicao para o anterior
        DB::table('assuntos')->where('id', $id)->update(['position' => $upPosicao]);


    }

    public function positionDown($id)
    {

        $posicao_atual = DB::table('assuntos')->where('id', $id)->first();
        $upPosicao = $posicao_atual->position+1;
        $posicao = $posicao_atual->position;

        //Coloca com a posicao do anterior
        DB::table('assuntos')->where('position', $upPosicao)->update(['position' => $posicao]);

        //atualiza a posicao para o anterior
        DB::table('assuntos')->where('id', $id)->update(['position' => $upPosicao]);

    }


}
