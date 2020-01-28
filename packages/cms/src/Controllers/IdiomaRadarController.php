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

class IdiomaRadarController extends Controller
{
    
    

    public function __construct()
    {
        $this->idiomaRadar = new \App\IdiomaRadar;
        $this->campos = [
            'imagem', 'titulo', 'idioma_sigla',
        ];
        $this->pathImagem = public_path().'/imagens/idiomas_radares';
        $this->sizesImagem = [
            'xs' => ['width' => 140, 'height' => 79],
            'sm' => ['width' => 480, 'height' => 270],
            'md' => ['width' => 580, 'height' => 326],
            'lg' => ['width' => 1170, 'height' => 658]
        ];
        $this->widthOriginal = true;
    }

    function index($radar_id)
    {
        $radar = \App\Radar::where('id', $radar_id)->first();
        $idiomas = \App\Idioma::lists('titulo', 'sigla')->all();

        return view('cms::idiomas_radares.listar', ['radar_id' => $radar->id, 'idiomas' => $idiomas]);
    }

    public function listar(Request $request)
    {

        $campos = explode(", ", $request->campos);

        $radares = DB::table('idiomas_radares')
            ->select($campos)
            ->where([
                [$request->campoPesquisa, 'ilike', "%$request->dadoPesquisa%"],
            ])
            ->where('radar_id', $request->radar_id)
            ->orderBy($request->ordem, $request->sentido)
            ->paginate($request->itensPorPagina);
        return $radares;
    }

    public function inserir(Request $request)
    {

        $data = $request->all();

        $data['radar'] += ['cmsuser_id' => auth()->guard('cms')->user()->id];//adiciona id do usuario

        /*if(empty($data['radar']['radar_id'])){
            $data['radar']['radar_id'] = 0;
        }*/

        //$data['radar']['tipo_valores'] = 0;

        //verifica se o index do campo existe no array e caso não exista inserir o campo com valor vazio.
        foreach($this->campos as $campo){
            if(!array_key_exists($campo, $data)){
                $data['radar'] += [$campo => ''];
            }
        }

        $file = $request->file('file');

        if($file!=null){
            $filename = rand(1000,9999)."-".clean($file->getClientOriginalName());
            $imagemCms = new ImagemCms();
            $success = $imagemCms->inserir($file, $this->pathImagem, $filename, $this->sizesImagem, $this->widthOriginal);
            
            if($success){
                $data['radar']['imagem'] = $filename;
                return $this->idiomaRadar->create($data['radar']);
            }else{
                return "erro";
            }
        }

        $radar = $this->idiomaRadar->create($data['radar']);

        return $radar;

    }

    public function detalhar($id)
    {
        $idioma_radar = $this->idiomaRadar
            ->where([
            ['id', '=', $id],
        ])->first();
        $idiomas = \App\Idioma::lists('titulo', 'sigla')->all();

        $radar_id = $idioma_radar->radar_id;

        return view('cms::idiomas_radares.detalhar', [
            'idioma_radar' => $idioma_radar,
            'idiomas' => $idiomas,
            'radar_id' => $radar_id
        ]);
    }

    public function alterar(Request $request, $id)
    {
        $data = $request->all();
        $data['radar'] += ['cmsuser_id' => auth()->guard('cms')->user()->id];//adiciona id do usuario

        //verifica se o index do campo existe no array e caso não exista inserir o campo com valor vazio.
        foreach($this->campos as $campo){
            if(!array_key_exists($campo, $data)){
                if($campo!='imagem'){
                    $data['radar'] += [$campo => ''];
                }
            }
        }

	    $data['radar']['tipo_valores'] = 0;

        $radar = $this->idiomaRadar->where([
            ['id', '=', $id],
        ])->firstOrFail();

        $file = $request->file('file');

        if($file!=null){
            $filename = rand(1000,9999)."-".clean($file->getClientOriginalName());
            $imagemCms = new ImagemCms();
            $success = $imagemCms->alterar($file, $this->pathImagem, $filename, $this->sizesImagem, $this->widthOriginal, $radar);
            if($success){
                $data['radar']['imagem'] = $filename;
                $radar->update($data['radar']);
                return $radar->imagem;
            }else{
                return "erro";
            }
        }

        //remover imagem
        if($data['removerImagem']){
            $data['radar']['imagem'] = '';
            if(file_exists($this->pathImagem."/".$radar->imagem)) {
                unlink($this->pathImagem . "/" . $radar->imagem);
            }
        }

        $radar->update($data['radar']);
        return "Gravado com sucesso";
    }

    public function excluir($id)
    {
        //Auth::loginUsingId(2);

        $radar = $this->idiomaRadar->where([
            ['id', '=', $id],
        ])->firstOrFail();

        //remover imagens        
        if(!empty($radar->imagem)){
            //remover imagens
            $imagemCms = new ImagemCms();
            $imagemCms->excluir($this->pathImagem, $this->sizesImagem, $radar);
        }
                

        $radar->delete();

    }

    
    


}
