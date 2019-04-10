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

    public function limparValoresSerie($serie_id, $abrangencia, $tipo_dados, $ano_pontos) {

        if($tipo_dados == 0){
            DB::table('valores_series')->where('serie_id', $serie_id)
                ->when($abrangencia > 0, function($query) use ($abrangencia){
                    return $query->where('tipo_regiao', $abrangencia);
                })->delete();
        }

        if($tipo_dados == 1){
            DB::table('geovalores')->where('serie_id', $serie_id)
                ->when($ano_pontos > 0, function($query) use ($ano_pontos){
                    return $query->whereRaw("EXTRACT(YEAR FROM data) = $ano_pontos");
                })->delete();
        }


    }


}
