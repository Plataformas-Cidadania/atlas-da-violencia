<?php

namespace Cms\Controllers;

use Cms\Models\ImagemCms;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Facades\Image;

class MensagemController extends Controller
{
    
    

    public function __construct()
    {
        $this->mensagem = new \App\Mensagem;
        $this->campos = [
            'origem', 'nome', 'email', 'telefone', 'titulo', 'mensagem', 'status', 'cmsuser_id',
        ];
        $this->pathImagem = public_path().'/imagens/mensagens';
        $this->sizesImagem = [
            'xs' => ['width' => 140, 'height' => 79],
            'sm' => ['width' => 480, 'height' => 270],
            'md' => ['width' => 580, 'height' => 326],
            'lg' => ['width' => 1170, 'height' => 658]
        ];
        $this->widthOriginal = true;
    }

    function index($origem)
    {

        $mensagens = \App\Mensagem::all();


        return view('cms::mensagem.listar', ['mensagens' => $mensagens, 'origem' => $origem]);
    }

    public function listar(Request $request)
    {

        $origem = $request->origem;


        //Auth::loginUsingId(2);

        $campos = explode(", ", $request->campos);

        $mensagens = DB::table('mensagens')
            ->select($campos)
            ->where([
                [$request->campoPesquisa, 'like', "%$request->dadoPesquisa%"],
                ['origem', $origem],
            ])
            ->orderBy($request->ordem, $request->sentido)
            ->paginate($request->itensPorPagina);

        foreach ($mensagens as $mensagem) {
            $mensagem->created_at =  date_format(date_create($mensagem->created_at), 'd-m-Y H:i:s');

            //Log::info($mensagem->created_at);
        }

        return $mensagens;
    }

    public function inserir(Request $request)
    {

        $data = $request->all();

        $data['mensagem'] += ['cmsuser_id' => auth()->guard('cms')->user()->id];//adiciona id do usuario

        //verifica se o index do campo existe no array e caso não exista inserir o campo com valor vazio.
        foreach($this->campos as $campo){
            if(!array_key_exists($campo, $data)){
                $data['mensagem'] += [$campo => ''];
            }
        }

        $file = $request->file('file');

        if($file!=null){
            $filename = rand(1000,9999)."-".clean($file->getClientOriginalName());
            $imagemCms = new ImagemCms();
            $success = $imagemCms->inserir($file, $this->pathImagem, $filename, $this->sizesImagem, $this->widthOriginal);
            
            if($success){
                $data['mensagem']['imagem'] = $filename;
                return $this->mensagem->create($data['mensagem']);
            }else{
                return "erro";
            }
        }

        return $this->mensagem->create($data['mensagem']);

    }

    public function detalhar($id)
    {
        $mensagem = $this->mensagem->where([
            ['id', '=', $id],
        ])->firstOrFail();



        return view('cms::mensagem.detalhar', ['mensagem' => $mensagem]);
    }

    public function alterar(Request $request, $id)
    {
        $data = $request->all();
        $data['mensagem'] += ['cmsuser_id' => auth()->guard('cms')->user()->id];//adiciona id do usuario

        //verifica se o index do campo existe no array e caso não exista inserir o campo com valor vazio.
        foreach($this->campos as $campo){
            if(!array_key_exists($campo, $data)){
                if($campo!='imagem'){
                    $data['mensagem'] += [$campo => ''];
                }
            }
        }
        $mensagem = $this->mensagem->where([
            ['id', '=', $id],
        ])->firstOrFail();

        $file = $request->file('file');

        if($file!=null){
            $filename = rand(1000,9999)."-".clean($file->getClientOriginalName());
            $imagemCms = new ImagemCms();
            $success = $imagemCms->alterar($file, $this->pathImagem, $filename, $this->sizesImagem, $this->widthOriginal, $mensagem);
            if($success){
                $data['mensagem']['imagem'] = $filename;
                $mensagem->update($data['mensagem']);
                return $mensagem->imagem;
            }else{
                return "erro";
            }
        }

        //remover imagem
        if($data['removerImagem']){
            $data['mensagem']['imagem'] = '';
            if(file_exists($this->pathImagem."/".$mensagem->imagem)) {
                unlink($this->pathImagem . "/" . $mensagem->imagem);
            }
        }

        $mensagem->update($data['mensagem']);
        return "Gravado com sucesso";
    }

    public function excluir($id)
    {
        //Auth::loginUsingId(2);

        $mensagem = $this->mensagem->where([
            ['id', '=', $id],
        ])->firstOrFail();

        //remover imagens        
        if(!empty($mensagem->imagem)){
            //remover imagens
            $imagemCms = new ImagemCms();
            $imagemCms->excluir($this->pathImagem, $this->sizesImagem, $mensagem);
        }
                

        $mensagem->delete();

    }
    public function status($id)
    {
        //$tipo_atual = DB::table('mensagens')->where('id', $id)->first();
        $status = 1;
        DB::table('mensagens')->where('id', $id)->update(['status' => $status]);

    }

    


}
