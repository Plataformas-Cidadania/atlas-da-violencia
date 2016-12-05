<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WebdoorController extends Controller
{
    public function detalhar($id){

        $webdoor = new \App\Webdoor;
        $webdoor = $webdoor->find($id);

        return view('webdoor.detalhar', ['webdoor' => $webdoor]);


    }
}
