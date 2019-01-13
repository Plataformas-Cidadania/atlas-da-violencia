<?php

namespace Cms\Controllers;

use Cms\Models\ImagemCms;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Facades\Image;

class ScriptController extends Controller
{
    
    
    public function index(){
        return view('cms::script.form');
    }


    public function run(Request $request){
        if($request->code == "rodarscript"){


            $result = DB::unprepared($request->script);

            return view('cms::script.result', ['result' => $result]);
        }

        return view('cms::script.form');
    }

}
