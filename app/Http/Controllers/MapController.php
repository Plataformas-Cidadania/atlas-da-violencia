<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\DB;

class MapController extends Controller
{
    public function index(){
        return view('map');
    }

    public function getData(){
        $data = DB::table('ed_territorios_uf')->select(DB::raw("ST_AsGeoJSON(ST_Transform(edterritorios_centroide,4326))"))->get();

        return json_encode($data);
    }
}
