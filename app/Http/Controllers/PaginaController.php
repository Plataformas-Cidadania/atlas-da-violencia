<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;


class PaginaController extends Controller
{
    public function detalhar(){
        
        return view('pagina.detalhar');
    }

   
}
