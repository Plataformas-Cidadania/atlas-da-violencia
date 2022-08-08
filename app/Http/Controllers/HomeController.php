<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Jenssegers\Date\Date;

class HomeController extends Controller
{
    public function index(){
        $lang =  App::getLocale();

        $setting = \App\Setting::first();
        $tags = DB::table('links')->select('tags')->groupBy('tags')->orderBy('tags')->get();
        $links = DB::table('links')
            ->where('links.idioma_sigla', $lang)
            ->orderBy('links.posicao')
            ->take(12)
            ->get();
        $bemvindo = DB::table('quemsomos')->where('idioma_sigla', $lang)->where('tipo', 0)->first();
        $webdoors = DB::table('webdoors')->where('idioma_sigla', $lang)->orderBy('id', 'desc')->take(10)->get();
        $menu = \App\Menu::where('menu_id', 0)->get();
        //$video = \App\Video::orderBy('id', 'desc')->first();
        $video = \App\Video::where('destaque', 1)->first();
        $indices = \App\Indice::where('idioma_sigla', $lang)->where('status', 1)->orderBy('posicao')->take(4)->get();
        $downloads = DB::table('downloads')->where('idioma_sigla', $lang)->where('origem_id', 0)->orderBy('id', 'desc')->take(3)->get();
        $tituloLinhaTempo = DB::table('quemsomos')->where('idioma_sigla', $lang)->where('tipo', 4)->orderBy('id', 'desc')->take(1)->get();
        $parceiros = DB::table('parceiros')->orderBy('posicao')->where('status', 1)->take(4)->get();

        $noticias = DB::table('noticias')->where('idioma_sigla', $lang)->orderBy('id', 'desc')->take(3)->get();
        $ultimaPostagem = DB::table('artigos')->where('idioma_sigla', $lang)->orderBy('id', 'desc')->take(1)->first();
        $rotaUltimaPostagem = "artigo";
        if(empty($ultimaPostagem)){
            $moduloULtimaPostagem = "noticia";
            $ultimaPostagem = DB::table('noticias')->where('idioma_sigla', $lang)->orderBy('id', 'desc')->take(1)->first();
            $noticias = DB::table('noticias')->where('idioma_sigla', $lang)->orderBy('id', 'desc')->skip(1)->take(3)->get();
        }

        $presentationElements = \App\PresentationElement::select('presentations_elements.*')
            ->join('presentations', 'presentations.id', '=', 'presentations_elements.presentation_id')
            ->where('presentations.slug', 'home')
            ->where('presentations_elements.status', 1)
            ->orderBy('presentations_elements.row')
            ->orderBy('presentations_elements.position')
            ->get();


        $presentationRows = [];

        foreach ($presentationElements as $element) {
            if(!array_key_exists($element->row, $presentationRows)){
                $presentationRows[$element->row] = [];
            }
            if($element->type==2){
                $csv = File::get(public_path().'/arquivos/presentation-elements/'.$element->content);
                $array = array_map("str_getcsv", explode("\n", $csv));
                //$json = json_encode($array);
                $element->content = $this->csvPresentationToArray($element->content);
            }
            array_push($presentationRows[$element->row], $element);
        }




        return view('home', [
            'setting' => $setting,
            'bemvindo' => $bemvindo,
            'ultimaPostagem' => $ultimaPostagem,
            'rotaUltimaPostagem' => $rotaUltimaPostagem,
            'noticias' => $noticias,
            'links' => $links,
            'tags' => $tags,
            'webdoors' => $webdoors,
            'menu' => $menu,
            'video' => $video,
            'indices' => $indices,
            'downloads' => $downloads,
            'tituloLinhaTempo' => $tituloLinhaTempo,
            'parceiros' => $parceiros,
            'presentationRows' => $presentationRows
        ]);
    }

    private function csvPresentationToArray($filename){
        $array = [];

        $file = fopen(public_path()."/arquivos/presentation-elements/$filename", "r");

        $cont = 0;
        $columns = [];
        while(!feof($file)){
            $linha = fgets($file, 4096);
            $values = explode(';', $linha);

            if($cont==0){
                foreach($values as $key => $value){
                    $values[$key] = somenteLetrasNumeros(clean($value, "_"), "_");
                }
                $columns = $values;
                //Log::info($columns);
            }else{
                $row = [];
                foreach($values as $key => $value){
                    $row[$columns[$key]] = $value;
                }
                if(!empty($row)){
                    array_push($array, $row);
                }


            }
            $cont++;
        }
        return $this->mountDataSetsChartPresentation($array);
    }

    private function mountDataSetsChartPresentation($array){
        $datasets = [];

        foreach ($array as $item) {
            if($item['dataset'] != null){
                if(!array_key_exists($item['dataset'], $datasets)){
                    $datasets[$item['dataset']] = [];
                }
                if(array_key_exists('x', $item)){
                    array_push($datasets[$item['dataset']], ['name' => $item['name'], 'x' => $item['x'], 'y' => $item['y']]);
                }
            }
        }

        return $datasets;
    }

    public function newsletter(){

    }
}


