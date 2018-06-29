<?php

namespace Cms\Controllers;

use Cms\Models\ImagemCms;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SettingController extends Controller
{

    public function __construct()
    {
        $this->setting = new \App\Setting;
        $this->campos = [
            'imagem', 'email', 'titulo', 'rodape', 'cep', 'endereco', 'numero', 'complemento', 'bairro', 'cidade', 'estado',
            'descricao_contato', 'telefone', 'telefone2', 'telefone3', 'facebook', 'youtube', 'pinterest', 'twitter', 'cor1', 'cor2', 'cor3', 'cor4', 'cor5',
            'serie_id',
        ];
        $this->pathImagem = public_path().'/imagens/settings';
        $this->sizesImagem = [
            'xs' => ['width' => 140, 'height' => 35],
            'sm' => ['width' => 380, 'height' => 95],
            'md' => ['width' => 520, 'height' => 130]

        ];
        $this->widthOriginal = true;
    }

    public function detalhar()
    {        
        $setting = $this->setting->firstOrFail();
       // $series = \App\Serie::lists('titulo', 'sigla')->all();


        $series = \App\Serie::join('textos_series', 'series.id', '=', 'textos_series.serie_id')
            ->join('valores_series', 'series.id', '=', 'valores_series.serie_id')
            ->where('valores_series.tipo_regiao', 1)
            ->orderBy('textos_series.titulo')
            ->lists('textos_series.titulo', 'series.id')
            ->all();



        return view('cms::setting.detalhar', ['setting' => $setting, 'series' => $series]);
    }

    public function alterar(Request $request)
    {
        /*$request->merge(['cmsuser_id'=>auth()->guard('cms')->user()->id]);//adiciona id do usuario no request
        $data = $request->all();

        //verifica se o index do campo existe no array e caso não exista inserir o campo com valor vazio.
        foreach($this->campos as $campo){
            if(!array_key_exists($campo, $data)){
                $data += [$campo => ''];
            }
        }

        $setting = $this->setting->where([
            ['id', '=', $id],
        ])->firstOrFail();
        $setting->update($data);
        return "Gravado com sucesso";*/


    $data = $request->all();
    $data['setting'] += ['cmsuser_id' => auth()->guard('cms')->user()->id];//adiciona id do usuario

    //verifica se o index do campo existe no array e caso não exista inserir o campo com valor vazio.
    foreach($this->campos as $campo){
        if(!array_key_exists($campo, $data)){
            if($campo!='imagem'){
                $data['setting'] += [$campo => ''];
            }
        }
    }

    $setting = $this->setting->firstOrFail();
    
    $file = $request->file('file');
    
    if($file!=null){
        $filename = rand(1000,9999)."-".clean($file->getClientOriginalName());
        $imagemCms = new ImagemCms();
        $success = $imagemCms->alterar($file, $this->pathImagem, $filename, $this->sizesImagem, $this->widthOriginal, $setting);
        if($success){
            $data['setting']['imagem'] = $filename;
            $setting->update($data['setting']);
            return $setting->imagem;
        }else{
            return "erro";
        }
    }
    
    //remover imagem
    if($data['removerImagem']){
        $data['setting']['imagem'] = '';
        if(file_exists($this->pathImagem."/".$setting->imagem)) {
            unlink($this->pathImagem . "/" . $setting->imagem);
        }
    }
    
    $setting->update($data['setting']);
    return "Gravado com sucesso";
    }
}
