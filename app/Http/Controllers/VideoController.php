<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\DB;

class VideoController extends Controller
{
    public function listar(){
        $videos = DB::table('videos')->paginate(10);
       
        return view('video.listar', ['videos' => $videos]);
    }
}
