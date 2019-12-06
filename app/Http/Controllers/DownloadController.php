<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use Zip;

//use Illuminate\Support\Facades\Storage;

class DownloadController extends Controller
{
    public function listar($origem = null, $origem_id = null){


        /*if($serie_id){
            $downloads = \App\Download::where('origem_id', $serie_id)->orderBy('id', 'desc')->paginate(25);*/

        if($origem_id){
            $downloads = \App\Download::where('origem', $origem)->where('origem_id', $origem_id)->orderBy('titulo')->paginate(25);

            return view('download.listar', ['downloads' => $downloads]);
        }

        $downloads = \App\Download::orderBy('id', 'desc')->paginate(25);
        return view('download.listar', ['downloads' => $downloads]);
    }

    public function detalhar($id){

        $download = new \App\Download;
        $download = $download->find($id);

        return view('download.detalhar', ['download' => $download]);
    }

    public function buscar(Request $request){

        $dados = $request->all();

        $busca = new \stdClass();
        $busca->titulo = $dados['busca'];
        /*$busca->tags = $dados['busca'];
        $busca->descricao = '';*/


        $downloads = DB::table('downloads')->where([
            ['titulo', 'ilike', "%$busca->titulo%"]
        ])/*->orWhere([
            ['tags', 'ilike', "%$busca->tags%"]
        ])*/->paginate(25);

        return view('download.listar', ['downloads' => $downloads]);

    }

    public function download($id){

        $arquivo = \App\Download::select('arquivo')->find($id)->arquivo;

        $zip = Zip::create(public_path().'/arquivos/downloads/'.$arquivo.'.zip');
        $zip->add(public_path().'/arquivos/downloads/'.$arquivo);
        $zip->close();

        return response()->download(public_path().'/arquivos/downloads/'.$arquivo.'.zip');

    }
}
