<?php

namespace Cms\Controllers;

use Cms\Models\ImagemCms;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Facades\Image;

class MenuController extends Controller
{
    
    

    public function __construct()
    {
        $this->menu = new \App\Menu;
        $this->campos = [
            'imagem', 'title', 'url', 'posicao', 'idioma_sigla', 'accesskey',
        ];
        $this->pathImagem = public_path().'/imagens/menus';
        $this->sizesImagem = [
            'xs' => ['width' => 140, 'height' => 79],
            'sm' => ['width' => 480, 'height' => 270],
            'md' => ['width' => 580, 'height' => 326],
            'lg' => ['width' => 1170, 'height' => 658]
        ];
        $this->widthOriginal = true;
    }

    function index($origem_id='', $origem_titulo='')
    {

        $menus = \App\Menu::all();
        $idiomas = \App\Idioma::lists('titulo', 'sigla')->all();

        return view('cms::menu.listar', ['menus' => $menus, 'idiomas' => $idiomas, 'origem_id' => $origem_id, 'origem_titulo' => $origem_titulo]);
    }

    public function listar(Request $request)
    {

        //Log::info('CAMPOS: '.$request->campos);

        //Auth::loginUsingId(2);

        $campos = explode(", ", $request->campos);

        $menus = DB::table('menu')
            ->select($campos)
            ->where([
                [$request->campoPesquisa, 'like', "%$request->dadoPesquisa%"],
            ])
            ->orderBy($request->ordem, $request->sentido)
            ->paginate($request->itensPorPagina);
        return $menus;
    }

    public function inserir(Request $request)
    {

        $data = $request->all();

        $data['menu'] += ['cmsuser_id' => auth()->guard('cms')->user()->id];//adiciona id do usuario
        $data['menu']['menu_id'] = 0;

        //verifica se o index do campo existe no array e caso não exista inserir o campo com valor vazio.
        foreach($this->campos as $campo){
            if(!array_key_exists($campo, $data)){
                $data['menu'] += [$campo => ''];
            }
        }

        $file = $request->file('file');

	//Log::info($request);

        if($file!=null){
            $filename = rand(1000,9999)."-".clean($file->getClientOriginalName());
            $imagemCms = new ImagemCms();
            $success = $imagemCms->inserir($file, $this->pathImagem, $filename, $this->sizesImagem, $this->widthOriginal);
            
            if($success){
                $data['menu']['imagem'] = $filename;
                return $this->menu->create($data['menu']);
            }else{
                return "erro";
            }
        }

        return $this->menu->create($data['menu']);

    }

    public function detalhar($id)
    {
        $menu = $this->menu->where([
            ['id', '=', $id],
        ])->firstOrFail();
        $idiomas = \App\Idioma::lists('titulo', 'sigla')->all();

        return view('cms::menu.detalhar', ['menu' => $menu, 'idiomas' => $idiomas]);
    }

    public function alterar(Request $request, $id)
    {
        $data = $request->all();
        $data['menu'] += ['cmsuser_id' => auth()->guard('cms')->user()->id];//adiciona id do usuario

        //verifica se o index do campo existe no array e caso não exista inserir o campo com valor vazio.
        foreach($this->campos as $campo){
            if(!array_key_exists($campo, $data)){
                if($campo!='imagem'){
                    $data['menu'] += [$campo => ''];
                }
            }
        }
        $menu = $this->menu->where([
            ['id', '=', $id],
        ])->firstOrFail();

        $file = $request->file('file');

        if($file!=null){
            $filename = rand(1000,9999)."-".clean($file->getClientOriginalName());
            $imagemCms = new ImagemCms();
            $success = $imagemCms->alterar($file, $this->pathImagem, $filename, $this->sizesImagem, $this->widthOriginal, $menu);
            if($success){
                $data['menu']['imagem'] = $filename;
                $menu->update($data['menu']);
                return $menu->imagem;
            }else{
                return "erro";
            }
        }

        //remover imagem
        if($data['removerImagem']){
            $data['menu']['imagem'] = '';
            if(file_exists($this->pathImagem."/".$menu->imagem)) {
                unlink($this->pathImagem . "/" . $menu->imagem);
            }
        }

        $menu->update($data['menu']);
        return "Gravado com sucesso";
    }

    public function excluir($id)
    {
        //Auth::loginUsingId(2);

        $menu = $this->menu->where([
            ['id', '=', $id],
        ])->firstOrFail();

        //remover imagens        
        if(!empty($menu->imagem)){
            //remover imagens
            $imagemCms = new ImagemCms();
            $imagemCms->excluir($this->pathImagem, $this->sizesImagem, $menu);
        }
                

        $menu->delete();

    }
    public function status($id)
    {
        $tipo_atual = DB::table('menu')->where('id', $id)->first();
        $status = $tipo_atual->status == 0 ? 1 : 0;
        DB::table('menu')->where('id', $id)->update(['status' => $status]);

    }

    public function positionUp($id)
    {

        $posicao_atual = DB::table('menu')->where('id', $id)->first();
        $upPosicao = $posicao_atual->posicao-1;
        $posicao = $posicao_atual->posicao;

        //Coloca com a posicao do anterior
        DB::table('menu')->where('posicao', $upPosicao)->update(['posicao' => $posicao]);

        //atualiza a posicao para o anterior
        DB::table('menu')->where('id', $id)->update(['posicao' => $upPosicao]);


    }

    public function positionDown($id)
    {

        $posicao_atual = DB::table('menu')->where('id', $id)->first();
        $upPosicao = $posicao_atual->posicao+1;
        $posicao = $posicao_atual->posicao;

        //Coloca com a posicao do anterior
        DB::table('menu')->where('posicao', $upPosicao)->update(['posicao' => $posicao]);

        //atualiza a posicao para o anterior
        DB::table('menu')->where('id', $id)->update(['posicao' => $upPosicao]);

    }
    


}
