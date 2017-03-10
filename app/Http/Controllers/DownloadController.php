<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
//use Illuminate\Support\Facades\Storage;

class DownloadController extends Controller
{
    public function listar(){
        $download = new \App\Download;
        $downloads = $download->orderBy('id', 'desc')->get();

        return view('download.listar', ['downloads' => $downloads]);
    }
}
