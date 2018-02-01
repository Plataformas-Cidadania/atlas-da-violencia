<?php

namespace Cms\Controllers;

use Cms\Models\ImagemCms;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Facades\Image;
use function MongoDB\BSON\toJSON;

class TemaController extends Controller
{

    public function __construct()
    {
        $this->tema = new \App\Tema;
        $this->campos = [
             'tema', 'cmsuser_id',
        ];
        $this->pathImagem = public_path().'/imagens/temas';
        $this->sizesImagem = [
            'xs' => ['width' => 32, 'height' => 32],
            'sm' => ['width' => 64, 'height' => 64],
            'md' => ['width' => 128, 'height' => 128],
            'lg' => ['width' => 256, 'height' => 256]
        ];
        $this->widthOriginal = true;
    }

    function index($tema_id)    {

        $tema = \App\Tema::find($tema_id);

        return view('cms::tema.listar', ['tema' => $tema, 'tema_id' => $tema_id]);

        //$temas = [];
        //$temas = $this->mountListTemas($temas, 0, "");

        //return view('cms::tema.listar', ['temas' => $temas]);

        //$temas = $this->mountArrayTemas(0);
        //return view('cms::teste-array', ['temas' => $temas]);

    }

    private function mountListTemas($arrayTemas, $tema_id, $nivel){
        $temas = \App\Tema::select('id', 'tema')->where('tema_id', $tema_id)->orderBy('id')->get();
        foreach($temas as $index => $tema){
            $titulo = $nivel.' - '.$tema->tema;
            if($nivel==""){
                $titulo = $tema->tema;
            }
            $arrayTemas[$tema->id] = $titulo;
            $arrayTemas = $this->mountListTemas($arrayTemas, $tema->id, $titulo);
        }
        return $arrayTemas;
    }

    private function mountArrayTemas($tema_id){
        $temas = \App\Tema::select('id', 'tema')->where('tema_id', $tema_id)->orderBy('id')->get();
        foreach($temas as $index => $tema){
            $temas[$index]['subtemas'] = $this->mountArrayTemas($tema->id);
        }
        return $temas;
    }

    public function listar(Request $request)
    {

        //Log::info('CAMPOS: '.$request->campos);

        //Auth::loginUsingId(2);

        $campos = explode(", ", $request->campos);

        $temas = DB::table('temas')
            ->select($campos)
            ->where([
                [$request->campoPesquisa, 'like', "%$request->dadoPesquisa%"],
                ['tema_id', '=', $request->tema_id],
            ])
            ->orderBy($request->ordem, $request->sentido)
            ->paginate($request->itensPorPagina);
        return $temas;
    }

    public function inserir(Request $request)
    {

        $data = $request->all();

        $data['tema'] += ['cmsuser_id' => auth()->guard('cms')->user()->id];//adiciona id do usuario

        //verifica se o index do campo existe no array e caso não exista inserir o campo com valor vazio.
        foreach($this->campos as $campo){
            if(!array_key_exists($campo, $data)){
                $data['tema'] += [$campo => ''];
            }
        }
        if($data['tema']['tema_id']==""){
            $data['tema']['tema_id'] = 0;
        }

        $file = $request->file('file');

        if($file!=null){
            $filename = rand(1000,9999)."-".clean($file->getClientOriginalName());
            $imagemCms = new ImagemCms();
            $success = $imagemCms->inserir($file, $this->pathImagem, $filename, $this->sizesImagem, $this->widthOriginal);
            
            if($success){
                $data['tema']['imagem'] = $filename;
                return $this->tema->create($data['tema']);
            }else{
                return "erro";
            }
        }

        return $this->tema->create($data['tema']);

    }

    public function detalhar($id)
    {

        //$temas = \App\Tema::lists('tema', 'id')->all();
        //$temas = [];
        //$temas = $this->mountListTemas($temas, 0, "");

        $tema = $this->tema->where([
            ['id', '=', $id],
        ])->firstOrFail();

        $tema_id = $tema->tema_id;

        return view('cms::tema.detalhar', ['tema' => $tema, 'tema_id' => $tema_id]);
    }

    public function alterar(Request $request, $id)
    {
        $data = $request->all();
        $data['tema'] += ['cmsuser_id' => auth()->guard('cms')->user()->id];//adiciona id do usuario

        //verifica se o index do campo existe no array e caso não exista inserir o campo com valor vazio.
        foreach($this->campos as $campo){
            if(!array_key_exists($campo, $data)){
                if($campo!='imagem'){
                    $data['tema'] += [$campo => ''];
                }
            }
        }
        if($data['tema']['tema_id']==""){
            $data['tema']['tema_id'] = 0;
        }
        $tema = $this->tema->where([
            ['id', '=', $id],
        ])->firstOrFail();

        $file = $request->file('file');

        if($file!=null){
            $filename = rand(1000,9999)."-".clean($file->getClientOriginalName());
            $imagemCms = new ImagemCms();
            $success = $imagemCms->alterar($file, $this->pathImagem, $filename, $this->sizesImagem, $this->widthOriginal, $tema);
            if($success){
                $data['tema']['imagem'] = $filename;
                $tema->update($data['tema']);
                return $tema->imagem;
            }else{
                return "erro";
            }
        }

        //remover imagem
        if($data['removerImagem']){
            $data['tema']['imagem'] = '';
            if(file_exists($this->pathImagem."/".$tema->imagem)) {
                unlink($this->pathImagem . "/" . $tema->imagem);
            }
        }

        $tema->update($data['tema']);
        return "Gravado com sucesso";
    }

    public function excluir($id)
    {
        //Auth::loginUsingId(2);

        $tema = $this->tema->where([
            ['id', '=', $id],
        ])->firstOrFail();

        //remover imagens        
        if(!empty($tema->imagem)){
            //remover imagens
            $imagemCms = new ImagemCms();
            $imagemCms->excluir($this->pathImagem, $this->sizesImagem, $tema);
        }
                

        $tema->delete();

    }

    


}
