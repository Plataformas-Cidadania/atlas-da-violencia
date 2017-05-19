<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
//use Illuminate\Support\Facades\Storage;

class DownloadController extends Controller
{
    public function listar($serie_id = null){

        if($serie_id){
            $downloads = \App\Download::where('origem_id', $serie_id)->orderBy('id', 'desc')->get();
            return view('download.listar', ['downloads' => $downloads]);
        }

        $downloads = \App\Download::orderBy('id', 'desc')->get();
        return view('download.listar', ['downloads' => $downloads]);
    }

    public function detalhar($id){

        $download = new \App\Download;
        $download = $download->find($id);

        return view('download.detalhar', ['download' => $download]);
    }
}
