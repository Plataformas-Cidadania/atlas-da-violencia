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
        //$data = DB::table('ed_territorios_uf')->select(DB::raw("ST_AsGeoJSON(ST_Transform(edterritorios_centroide,4326))"))->get();
        //$data = DB::table('ed_territorios_uf')->select(DB::raw("edterritorios_centroide"))->get();
        //$data = DB::table('ed_territorios_uf')->select(DB::raw("ST_X(edterritorios_centroide), ST_Y(edterritorios_centroide)"))->get();
        //$data = DB::table('ed_territorios_uf')->select(DB::raw("ST_AsText(edterritorios_geometry)"))->get();
        //$data = DB::table('ed_territorios_uf')->select(DB::raw("ST_AsGeoJSON(ST_Transform(edterritorios_geometry,4326))"))->get();
        $data = DB::table('ed_territorios_uf')->select(DB::raw("ST_AsGeoJSON(edterritorios_geometry)"))->get();

        return $data;
    }
}
