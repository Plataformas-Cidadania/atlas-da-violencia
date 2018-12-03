<?php

namespace Cms\Controllers;

use Cms\Models\ImagemCms;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Facades\Image;

class ConsultaController extends Controller
{
    
    

    public function __construct()
    {
        $this->consulta = new \App\Consulta;
        $this->idiomaConsulta = new \App\IdiomaConsulta;
        $this->campos = [
            'imagem', 'periodicidade_id', 'tema_id', 'unidade_id', 'arquivo', 'titulo', 'url', 'cmsuser_id',
        ];
        $this->pathImagem = public_path().'/imagens/consultas';
        $this->sizesImagem = [
            'xs' => ['width' => 34, 'height' => 23],
            'sm' => ['width' => 50, 'height' => 34],
            'md' => ['width' => 100, 'height' => 68],
            'lg' => ['width' => 150, 'height' => 101]
        ];
        $this->widthOriginal = true;
	    $this->pathArquivo = public_path().'/arquivos/consultas';
    }

    function index()
    {

        $consultas = \App\Consulta::all();
        $periodicidades = \App\Periodicidade::lists('titulo', 'id')->all();
        $temas = \App\Tema::lists('tema', 'id')->all();
        $unidades = \App\Unidade::lists('titulo', 'id')->all();
        $idiomas = \App\Idioma::lists('titulo', 'sigla')->all();

        return view('cms::consulta.listar', [
            'consultas' => $consultas,
            'periodicidades' => $periodicidades,
            'temas' => $temas,
            'unidades' => $unidades,
            'idiomas' => $idiomas
        ]);
    }

    public function listar(Request $request)
    {

        //Log::info('CAMPOS: '.$request->campos);

        //Auth::loginUsingId(2);

        $campos = explode(", ", $request->campos);

        $consultas = DB::table('consultas')
            ->select($campos)
            ->join('idiomas_consultas', 'idiomas_consultas.consulta_id', '=', 'consultas.id')
            ->where([
                [$request->campoPesquisa, 'like', "%$request->dadoPesquisa%"],
                ['idiomas_consultas.idioma_sigla', 'pt_BR'],
            ])
            ->orderBy($request->ordem, $request->sentido)
            ->paginate($request->itensPorPagina);


        return $consultas;
    }

    public function inserir(Request $request)
    {

        $data = $request->all();

        $data['consulta'] += ['cmsuser_id' => auth()->guard('cms')->user()->id];//adiciona id do usuario
        $data['idioma'] += ['cmsuser_id' => auth()->guard('cms')->user()->id];//adiciona id do usuario

        //verifica se o index do campo existe no array e caso não exista inserir o campo com valor vazio.
        foreach($this->campos as $campo){
            if(!array_key_exists($campo, $data)){
                $data['consulta'] += [$campo => ''];
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
                $data['consulta']['imagem'] = $filename;
            }
        }

        $successArquivo = true;
        if($arquivo!=null){
            $filenameArquivo = rand(1000,9999)."-".clean($arquivo->getClientOriginalName());
            $successArquivo = $arquivo->move($this->pathArquivo, $filenameArquivo);
            if($successArquivo){
                $data['consulta']['arquivo'] = $filenameArquivo;
            }
        }

        if($successFile && $successArquivo){
            $inserir = $this->consulta->create($data['consulta']);
            $data['idioma']['consulta_id'] = $inserir->id;
            $inserir2 = $this->idiomaConsulta->create($data['idioma']);
        }else{
            return "erro";
        }


        return $inserir;

        //return $this->consulta->create($data['consulta']);

    }

    public function detalhar($id)
    {
        $consulta = $this->consulta->where([
            ['id', '=', $id],
        ])->firstOrFail();

        $periodicidades = \App\Periodicidade::lists('titulo', 'id')->all();
        $temas = \App\Tema::lists('tema', 'id')->all();
        $unidades = \App\Unidade::lists('titulo', 'id')->all();
        $idiomas = \App\Idioma::lists('titulo', 'sigla')->all();

        return view('cms::consulta.detalhar', [
            'consulta' => $consulta,
            'periodicidades' => $periodicidades,
            'temas' => $temas,
            'unidades' => $unidades,
            'idiomas' => $idiomas
        ]);
    }

    public function alterar(Request $request, $id)
    {
        $data = $request->all();
        $data['consulta'] += ['cmsuser_id' => auth()->guard('cms')->user()->id];//adiciona id do usuario

        //verifica se o index do campo existe no array e caso não exista inserir o campo com valor vazio.
        foreach($this->campos as $campo){
            if(!array_key_exists($campo, $data)){
                if($campo!='imagem' && $campo!='arquivo'){
                    $data['consulta'] += [$campo => ''];
                }
            }
        }
        $consulta = $this->consulta->where([
            ['id', '=', $id],
        ])->firstOrFail();

        $file = $request->file('file');
	    $arquivo = $request->file('arquivo');

        //remover imagem
        if($data['removerImagem']){
            $data['consulta']['imagem'] = '';
            if(file_exists($this->pathImagem."/".$consulta->imagem)) {
                unlink($this->pathImagem . "/" . $consulta->imagem);
            }
        }

        if($data['removerArquivo']){
            $data['consulta']['arquivo'] = '';
            if(file_exists($this->pathArquivo."/".$consulta->arquivo)) {
                unlink($this->pathArquivo . "/" . $consulta->arquivo);
            }
        }

	$successFile = true;
        if($file!=null){
            $filename = rand(1000,9999)."-".clean($file->getClientOriginalName());
            $imagemCms = new ImagemCms();
            $successFile = $imagemCms->alterar($file, $this->pathImagem, $filename, $this->sizesImagem, $this->widthOriginal, $consulta);
            if($successFile){
                $data['consulta']['imagem'] = $filename;
            }
        }
	$successArquivo = true;
        if($arquivo!=null){
            $filenameArquivo = rand(1000,9999)."-".clean($arquivo->getClientOriginalName());
            $successArquivo = $arquivo->move($this->pathArquivo, $filenameArquivo);
            if($successArquivo){
                $data['consulta']['arquivo'] = $filenameArquivo;
            }
        }

        if($successFile && $successArquivo){

            $consulta->update($data['consulta']);
            return $consulta->imagem;
        }else{
            return "erro";
        }
///
        
        /*$consulta->update($data['consulta']);
        return "Gravado com sucesso";*/
    }

    public function excluir($id)
    {
        //Auth::loginUsingId(2);

        $consulta = $this->consulta->where([
            ['id', '=', $id],
        ])->firstOrFail();

        //remover imagens        
        if(!empty($consulta->imagem)){
            //remover imagens
            $imagemCms = new ImagemCms();
            $imagemCms->excluir($this->pathImagem, $this->sizesImagem, $consulta);
        }
        if(!empty($consulta->arquivo)) {
            if (file_exists($this->pathArquivo . "/" . $consulta->arquivo)) {
                unlink($this->pathArquivo . "/" . $consulta->arquivo);
            }
        }

        $consulta->delete();

    }
    public function status($id)
    {
        $tipo_atual = DB::table('consultas')->where('id', $id)->first();
        $status = $tipo_atual->status == 0 ? 1 : 0;
        DB::table('consultas')->where('id', $id)->update(['status' => $status]);

    }

    


}
