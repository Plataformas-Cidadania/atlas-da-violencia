<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\DB;

class ParceiroController extends Controller
{
    public function index(){
        $parceiros = DB::table('parceiros')->orderBy('posicao')->where('status', 1)->get();

        return view('parceiro.listar', ['parceiros' => $parceiros]);
    }
}
