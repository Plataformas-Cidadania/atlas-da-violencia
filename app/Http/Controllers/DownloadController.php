<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\DB;

class DownloadController extends Controller
{
    public function listar(){
        //$downloads = DB::table('downloads')->paginate(10);
       
        //return view('download.listar', ['downloads' => $downloads]);
        return view('download.listar');
    }
}
