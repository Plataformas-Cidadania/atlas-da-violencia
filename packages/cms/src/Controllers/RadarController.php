<?php

namespace Cms\Controllers;

use Cms\Models\ImagemCms;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Facades\Image;

class RadarController extends Controller
{
    

    public function __construct()
    {
        $this->radar = new \App\Radar;
        $this->idiomaRadar = new \App\IdiomaRadar;
        $this->campos = [
            'imagem', 'icone', 'titulo', 'tipo', 'slug', 'cmsuser_id',
        ];
        $this->pathImagem = public_path().'/imagens/radares';
        $this->sizesImagem = [
            'xs' => ['width' => 34, 'height' => 23],
            'sm' => ['width' => 50, 'height' => 34],
            'md' => ['width' => 100, 'height' => 68],
            'lg' => ['width' => 150, 'height' => 101]
        ];
        $this->pathIcone = public_path().'/imagens/radares_icones';
        $this->sizesIcone = [
            'xs' => ['width' => 34, 'height' => 23],
            'sm' => ['width' => 50, 'height' => 34],
            'md' => ['width' => 100, 'height' => 68],
            'lg' => ['width' => 150, 'height' => 101]
        ];
        $this->widthOriginal = true;
    }

    function index()
    {

        $radares = \App\Radar::all();
        $idiomas = \App\Idioma::lists('titulo', 'sigla')->all();

        return view('cms::radar.listar', ['radares' => $radares, 'idiomas' => $idiomas]);
    }

    public function listar(Request $request)
    {

        //Log::info('CAMPOS: '.$request->campos);

        //Auth::loginUsingId(2);

        $campos = explode(", ", $request->campos);

        /*$radares = DB::table('radares')
            ->select($campos)
            ->where([
                [$request->campoPesquisa, 'like', "%$request->dadoPesquisa%"],
            ])
            ->orderBy($request->ordem, $request->sentido)
            ->paginate($request->itensPorPagina);*/

        $radares = DB::table('radares')
        ->select($campos)
        ->join('idiomas_radares', 'idiomas_radares.radar_id', '=', 'radares.id')
        ->where([
            [$request->campoPesquisa, 'like', "%$request->dadoPesquisa%"],
            ['idiomas_radares.idioma_sigla', 'pt_BR'],
        ])
        ->orderBy($request->ordem, $request->sentido)
        ->paginate($request->itensPorPagina);


        return $radares;
    }

    public function inserir(Request $request)
    {

        $data = $request->all();

        //$data['radar'] = [];

        $data['radar'] += ['cmsuser_id' => auth()->guard('cms')->user()->id];//adiciona id do usuario
        $data['idioma'] += ['cmsuser_id' => auth()->guard('cms')->user()->id];//adiciona id do usuario

        //verifica se o index do campo existe no array e caso não exista inserir o campo com valor vazio.
        foreach($this->campos as $campo){
            if(!array_key_exists($campo, $data)){
                $data['radar'] += [$campo => ''];
            }
        }

        $file = $request->file('file');
        $fileIcone = $request->file('fileIcone');

        if($file!=null){
            $imagemCms = new ImagemCms();

            $filename = rand(1000,9999)."-".clean($file->getClientOriginalName());
            $success = $imagemCms->inserir($file, $this->pathImagem, $filename, $this->sizesImagem, $this->widthOriginal);

            $filenameIcone = rand(1000,9999)."-".clean($fileIcone->getClientOriginalName());
            $success = $imagemCms->inserir($fileIcone, $this->pathIcone, $filenameIcone, $this->sizesIcone, $this->widthOriginal);
            
            if($success){
                $data['radar']['imagem'] = $filename;
                $data['radar']['icone'] = $filenameIcone;
                $inserir = $this->radar->create($data['radar']);

                $data['idioma']['radar_id'] = $inserir->id;
                $inserir2 = $this->idiomaRadar->create($data['idioma']);

                return $inserir;
            }else{
                return "erro";
            }
        }

        //return $this->radar->create($data['radar']);

        $inserir = $this->radar->create($data['radar']);
        $data['idioma']['radar_id'] = $inserir->id;
        $inserir2 = $this->idiomaRadar->create($data['idioma']);
        return $inserir;

    }

    public function detalhar($id)
    {
        $radar = $this->radar->where([
            ['id', '=', $id],
        ])->firstOrFail();
        return view('cms::radar.detalhar', ['radar' => $radar]);
    }

    public function alterar(Request $request, $id)
    {

        $data = $request->all();
        //$data['radar'] = [];
        $data['radar'] += ['cmsuser_id' => auth()->guard('cms')->user()->id];//adiciona id do usuario

        //verifica se o index do campo existe no array e caso não exista inserir o campo com valor vazio.
        foreach($this->campos as $campo){
            if(!array_key_exists($campo, $data)){
                if($campo!='imagem' && $campo!='icone'){
                    $data['radar'] += [$campo => ''];
                }
            }
        }
        $radar = $this->radar->where([
            ['id', '=', $id],
        ])->firstOrFail();

        $file = $request->file('file');
        $fileIcone = $request->file('fileIcone');

        /*if($file!=null){
            $filename = rand(1000,9999)."-".clean($file->getClientOriginalName());
            $filenameIcone = rand(1000,9999)."-".clean($fileIcone->getClientOriginalName());

            $imagemCms = new ImagemCms();
            $success = $imagemCms->alterar($file, $this->pathImagem, $filename, $this->sizesImagem, $this->widthOriginal, $radar);
            $success = $imagemCms->alterar($fileIcone, $this->pathImagemIcone, $filenameIcone, $this->sizesImagemIcone, $this->widthOriginal, $radar);

            if($success){
                $data['radar']['imagem'] = $filename;
                $data['radar']['icone'] = $filenameIcone;
                $radar->update($data['radar']);
                return $radar->imagem;
            }else{
                return "erro";
            }
        }*/




        $successFile = true;
        if($file!=null){
            $filename = rand(1000,9999)."-".clean($file->getClientOriginalName());
            $imagemCms = new ImagemCms();
            $successFile = $imagemCms->alterar($file, $this->pathImagem, $filename, $this->sizesImagem, $this->widthOriginal, $radar);
            if($successFile){
                $data['radar']['imagem'] = $filename;
            }
        }
        $successFileIcone = true;
        if($fileIcone!=null){
            $filenameIcone = rand(1000,9999)."-".clean($fileIcone->getClientOriginalName());
            $imagemCms = new ImagemCms();
            $successFileIcone = $imagemCms->alterar($fileIcone, $this->pathIcone, $filenameIcone, $this->sizesIcone, $this->widthOriginal, $radar);
            if($successFileIcone){
                $data['radar']['icone'] = $filenameIcone;
            }
        }

        //remover imagem
        if($data['removerImagem']){
            $data['radar']['imagem'] = '';
            if(file_exists($this->pathImagem."/".$radar->imagem)) {
                unlink($this->pathImagem . "/" . $radar->imagem);
            }
        }
        //remover icone
        if($data['removerIcone']){
            $data['radar']['icone'] = '';
            if(file_exists($this->pathIcone."/".$radar->icone)) {
                unlink($this->pathIcone . "/" . $radar->icone);
            }
        }

        //$radar->update($data['radar']);
        //return "Gravado com sucesso";

        if($successFile && $successFileIcone){
            $radar->update($data['radar']);
            return $radar->imagem;
        }else{
            return "erro";
        }
    }

    public function excluir($id)
    {
        //Auth::loginUsingId(2);

        $radar = $this->radar->where([
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
