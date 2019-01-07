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

class TemaController extends Controller
{

    public function __construct()
    {
        $this->tema = new \App\Tema;
        $this->idiomaTema = new \App\IdiomaTema;
        $this->campos = [
              'tema', 'tipo','cmsuser_id',
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

    function index($tema_id = 0)    {

        $tema = \App\Tema::find($tema_id);
        $idiomas = \App\Idioma::lists('titulo', 'sigla')->all();

        return view('cms::tema.listar', ['tema' => $tema, 'tema_id' => $tema_id, 'idiomas' => $idiomas]);

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
            ->join('idiomas_temas', 'idiomas_temas.tema_id', '=', 'temas.id')
            ->where([
                [$request->campoPesquisa, 'ilike', "%$request->dadoPesquisa%"],
                ['temas.tema_id', '=', $request->tema_id],
		        ['idiomas_temas.idioma_sigla', 'pt_BR'],
            ])
            ->orderBy($request->ordem, $request->sentido)
            ->paginate($request->itensPorPagina);
        return $temas;
    }

    public function inserir(Request $request)
    {

        $data = $request->all();

        $data['tema'] += ['cmsuser_id' => auth()->guard('cms')->user()->id];//adiciona id do usuario
        $data['idioma'] += ['cmsuser_id' => auth()->guard('cms')->user()->id];//adiciona id do usuario

        //verifica se o index do campo existe no array e caso não exista inserir o campo com valor vazio.
        foreach($this->campos as $campo){
            if(!array_key_exists($campo, $data['tema'])){
                $data['tema'] += [$campo => ''];
            }
        }
        //$data['tema']['tema'] = '';


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
		        $inserir = $this->tema->create($data['tema']);
	            $data['idioma']['tema_id'] = $inserir->id;
       		    $inserir2 = $this->idiomaTema->create($data['idioma']);
	            return $inserir;

            }else{
                return "erro";
            }
        }

        $inserir = $this->tema->create($data['tema']);
        $data['idioma']['tema_id'] = $inserir->id;
        $inserir2 = $this->idiomaTema->create($data['idioma']);
        return $inserir;

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

    public function status($id)
    {
        $tipo_atual = DB::table('temas')->where('id', $id)->first();
        $status = $tipo_atual->status == 0 ? 1 : 0;
        DB::table('temas')->where('id', $id)->update(['status' => $status]);

    }

    


}
