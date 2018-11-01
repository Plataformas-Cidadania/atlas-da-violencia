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

class IdiomaConsultaController extends Controller
{
    
    

    public function __construct()
    {
        $this->idiomaConsulta = new \App\IdiomaConsulta;
        $this->campos = [
            'imagem', 'titulo', 'idioma_sigla',
        ];
        $this->pathImagem = public_path().'/imagens/idiomas_consultas';
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
        $idiomas = \App\Idioma::lists('titulo', 'sigla')->all();

        return view('cms::idiomas_consultas.listar', ['consulta_id' => $consulta->id, 'idiomas' => $idiomas]);
    }

    public function listar(Request $request)
    {

        $campos = explode(", ", $request->campos);

        $consultas = DB::table('idiomas_consultas')
            ->select($campos)
            ->where([
                [$request->campoPesquisa, 'ilike', "%$request->dadoPesquisa%"],
            ])
            ->where('consulta_id', $request->consulta_id)
            ->orderBy($request->ordem, $request->sentido)
            ->paginate($request->itensPorPagina);


        return $consultas;
    }

    public function inserir(Request $request)
    {

        $data = $request->all();

        $data['consulta'] += ['cmsuser_id' => auth()->guard('cms')->user()->id];//adiciona id do usuario

        /*if(empty($data['consulta']['consulta_id'])){
            $data['consulta']['consulta_id'] = 0;
        }*/

        //$data['consulta']['tipo_valores'] = 0;

        //verifica se o index do campo existe no array e caso não exista inserir o campo com valor vazio.
        foreach($this->campos as $campo){
            if(!array_key_exists($campo, $data)){
                $data['consulta'] += [$campo => ''];
            }
        }

        $file = $request->file('file');

        if($file!=null){
            $filename = rand(1000,9999)."-".clean($file->getClientOriginalName());
            $imagemCms = new ImagemCms();
            $success = $imagemCms->inserir($file, $this->pathImagem, $filename, $this->sizesImagem, $this->widthOriginal);
            
            if($success){
                $data['consulta']['imagem'] = $filename;
                return $this->idiomaConsulta->create($data['consulta']);
            }else{
                return "erro";
            }
        }

        $consulta = $this->idiomaConsulta->create($data['consulta']);

        return $consulta;

    }

    public function detalhar($id)
    {
        $idioma_consulta = $this->idiomaConsulta
            ->where([
            ['id', '=', $id],
        ])->first();
        $idiomas = \App\Idioma::lists('titulo', 'sigla')->all();

        $consulta_id = $idioma_consulta->consulta_id;

        return view('cms::idiomas_consultas.detalhar', [
            'idioma_consulta' => $idioma_consulta,
            'idiomas' => $idiomas,
            'consulta_id' => $consulta_id
        ]);
    }

    public function alterar(Request $request, $id)
    {
        $data = $request->all();
        $data['consulta'] += ['cmsuser_id' => auth()->guard('cms')->user()->id];//adiciona id do usuario

        //verifica se o index do campo existe no array e caso não exista inserir o campo com valor vazio.
        foreach($this->campos as $campo){
            if(!array_key_exists($campo, $data)){
                if($campo!='imagem'){
                    $data['consulta'] += [$campo => ''];
                }
            }
        }

	    $data['consulta']['tipo_valores'] = 0;

        $consulta = $this->idiomaConsulta->where([
            ['id', '=', $id],
        ])->firstOrFail();

        $file = $request->file('file');

        if($file!=null){
            $filename = rand(1000,9999)."-".clean($file->getClientOriginalName());
            $imagemCms = new ImagemCms();
            $success = $imagemCms->alterar($file, $this->pathImagem, $filename, $this->sizesImagem, $this->widthOriginal, $consulta);
            if($success){
                $data['consulta']['imagem'] = $filename;
                $consulta->update($data['consulta']);
                return $consulta->imagem;
            }else{
                return "erro";
            }
        }

        //remover imagem
        if($data['removerImagem']){
            $data['consulta']['imagem'] = '';
            if(file_exists($this->pathImagem."/".$consulta->imagem)) {
                unlink($this->pathImagem . "/" . $consulta->imagem);
            }
        }

        $consulta->update($data['consulta']);
        return "Gravado com sucesso";
    }

    public function excluir($id)
    {
        //Auth::loginUsingId(2);

        $consulta = $this->idiomaConsulta->where([
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
