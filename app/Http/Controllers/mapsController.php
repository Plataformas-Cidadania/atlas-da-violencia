<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class mapsController extends Controller
{
    public function index(){
        return view('maps');
    }
}
