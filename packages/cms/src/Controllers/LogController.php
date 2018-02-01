<?php

namespace Cms\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class LogController extends Controller
{
    public function index(){


        return view('cms:logs.listar');
    }
}
