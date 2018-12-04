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

class TemaConsultaController extends Controller
{
    
    private $subtemas = [];

    public function __construct()
    {
        $this->temaConsulta = new \App\TemaConsulta;
        $this->campos = [
            'temas_id', 'consulta_id',
        ];
        $this->pathImagem = public_path().'/imagens/temas_consultas';
        $this->sizesImagem = [
            'xs' => ['width' => 140, 'height' => 79],
            'sm' => ['width' => 480, 'height' => 270],
            'md' => ['width' => 580, 'height' => 326],
            'lg' => ['width' => 1170, 'height' => 658]
        ];
        $this->widthOriginal = true;
    }

    function index($consulta_id)
    {
        $consulta = \App\Consulta::where('id', $consulta_id)->first();
        $temas = [];
        $temas = $this->mountListSubTemas($temas, 0, "");

        //Log::info($this->subtemas);

        return view('cms::temas_consultas.listar', ['consulta_id' => $consulta->id, 'temas' => $this->subtemas]);
    }

    private function mountListSubTemas($arrayTemas, $tema_id, $nivel){
        $temas = \App\Tema::select('temas.id', DB::Raw('idiomas_temas.titulo as tema'))
            ->join('idiomas_temas', 'idiomas_temas.tema_id', '=', 'temas.id')
            ->where('temas.tema_id', $tema_id)
            ->where('idiomas_temas.idioma_sigla', 'pt_BR')
            ->orderBy('temas.id')
            ->get();
        //monta um array apenas com os nós folhas (níveis que não possuem filhos)
        if(count($temas)===0){
            $pai = \App\Tema::find($tema_id);
            $this->subtemas[$pai->id] = $nivel;
        }
        foreach($temas as $index => $tema){
            $titulo = $nivel.' - '.$tema->tema;
            if($nivel==""){
                $titulo = $tema->tema;
            }
            $arrayTemas[$tema->id] = $titulo;
            $arrayTemas = $this->mountListSubTemas($arrayTemas, $tema->id, $titulo);
        }
        return $arrayTemas;
    }

    private function mountListTemas($arrayTemas, $tema_id, $nivel){
        $temas = \App\Tema::select('temas.id', DB::Raw('idiomas_temas.titulo as tema'))
            ->join('idiomas_temas', 'idiomas_temas.tema_id', '=', 'temas.id')
            ->where('temas.tema_id', $tema_id)
            ->where('idiomas_temas.idioma_sigla', 'pt_BR')
            ->orderBy('temas.id')
            ->get();
        //monta um array apenas com os nós folhas (níveis que não possuem filhos)
        if(count($temas)===0){
            array_push($this->subtemas, $nivel);
        }
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

    public function listar(Request $request)
    {

        $campos = explode(", ", $request->campos);

        $consultas = DB::table('temas_consultas')
            ->select($campos)
            ->join('consultas', 'consultas.id', '=', 'temas_consultas.consulta_id')
            ->join('temas', 'temas.id', '=', 'temas_consultas.tema_id')
            ->join('idiomas_temas', 'idiomas_temas.tema_id', '=', 'temas.id')
            ->where([
                [$request->campoPesquisa, 'ilike', "%$request->dadoPesquisa%"],
            ])
            ->where('temas_consultas.consulta_id', $request->consulta_id)
            ->where('idiomas_temas.idioma_sigla', 'pt_BR')
            ->orderBy($request->ordem, $request->sentido)
            ->paginate($request->itensPorPagina);
        return $consultas;
    }

    public function inserir(Request $request)
    {

        $data = $request->all();

        $data['tema'] += ['cmsuser_id' => auth()->guard('cms')->user()->id];//adiciona id do usuario

        /*if(empty($data['tema']['consulta_id'])){
            $data['tema']['consulta_id'] = 0;
        }*/

        //$data['tema']['tipo_valores'] = 0;

        //verifica se o index do campo existe no array e caso não exista inserir o campo com valor vazio.
        foreach($this->campos as $campo){
            if(!array_key_exists($campo, $data)){
                $data['tema'] += [$campo => ''];
            }
        }

        $file = $request->file('file');

        if($file!=null){
            $filename = rand(1000,9999)."-".clean($file->getClientOriginalName());
            $imagemCms = new ImagemCms();
            $success = $imagemCms->inserir($file, $this->pathImagem, $filename, $this->sizesImagem, $this->widthOriginal);
            
            if($success){
                $data['tema']['imagem'] = $filename;
                return $this->temaConsulta->create($data['tema']);
            }else{
                return "erro";
            }
        }

        $consulta = $this->temaConsulta->create($data['tema']);

        return $consulta;

    }

    public function detalhar($id)
    {
        $tema_consulta = $this->temaConsulta
            ->where([
            ['id', '=', $id],
        ])->first();
        $temas = [];
        $temas = $this->mountListTemas($temas, 0, "");

        $consulta_id = $tema_consulta->consulta_id;

        return view('cms::temas_consultas.detalhar', [
            'tema_consulta' => $tema_consulta,
            'temas' => $temas,
            'consulta_id' => $consulta_id
        ]);
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

	    $data['tema']['tipo_valores'] = 0;

        $consulta = $this->temaConsulta->where([
            ['id', '=', $id],
        ])->firstOrFail();

        $file = $request->file('file');

        if($file!=null){
            $filename = rand(1000,9999)."-".clean($file->getClientOriginalName());
            $imagemCms = new ImagemCms();
            $success = $imagemCms->alterar($file, $this->pathImagem, $filename, $this->sizesImagem, $this->widthOriginal, $consulta);
            if($success){
                $data['tema']['imagem'] = $filename;
                $consulta->update($data['tema']);
                return $consulta->imagem;
            }else{
                return "erro";
            }
        }

        //remover imagem
        if($data['removerImagem']){
            $data['tema']['imagem'] = '';
            if(file_exists($this->pathImagem."/".$consulta->imagem)) {
                unlink($this->pathImagem . "/" . $consulta->imagem);
            }
        }

        $consulta->update($data['tema']);
        return "Gravado com sucesso";
    }

    public function excluir($id)
    {
        //Auth::loginUsingId(2);

        $consulta = $this->temaConsulta->where([
            ['id', '=', $id],
        ])->firstOrFail();

        //remover imagens        
        if(!empty($consulta->imagem)){
            //remover imagens
            $imagemCms = new ImagemCms();
            $imagemCms->excluir($this->pathImagem, $this->sizesImagem, $consulta);
        }
                

        $consulta->delete();

    }

    
    


}
