<?php

namespace Cms\Controllers;

use Cms\Models\ImagemCms;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Facades\Image;
use Maatwebsite\Excel\Facades\Excel;

class ValoresSerieController extends Controller
{
    
    public function index($serie_id){

        $valores = \App\ValorSerie::where('serie_id', $serie_id)->get();
        $textos_series = \App\TextoSerie::select()
            ->where('serie_id', $serie_id)->where('idioma_sigla', 'pt_BR')->first();

        return view('cms::serie.valores-serie', ['valores' => $valores, 'serie_id' => $serie_id, 'textos_series' => $textos_series]);
    }

    public function valores(Request $request) {

    }


}
