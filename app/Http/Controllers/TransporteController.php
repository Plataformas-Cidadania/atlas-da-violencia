<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TransporteController extends Controller
{



    public function __construct(){
        $this->TIPOS_TRANSPORTES = config('constants.TIPOS_TRANSPORTES');
        $this->MESES_ABREVIADOS = config('constants.MESES_ABREVIADOS');
    }

    public function bus(){
        $links = DB::table('links')->paginate(20);

        return view('transporte.bus', ['links' => $links]);
    }
    public function brt(){
        return view('transporte.brt');
    }
    public function stations(){
        return view('transporte.estacoes-react');
    }
    public function radar(){
        return view('transporte.radar');
    }
    public function metro(){

        return view('transporte.metro');
    }


    public function bus2(){
        $links = DB::table('links')->paginate(20);

        return view('transporte.bus2', ['links' => $links]);
    }

    public function getBus($linha){

        $dateHoje = date('Y-m-d');
        $dateHojeHora = date('Y-m-d H:i:s');
        $dateMenosTrinta = date('Y-m-d', strtotime('-25 days', strtotime($dateHoje)));
        $dateMenosUm = date('Y-m-d H:i:s', strtotime('-24 Hour', strtotime($dateHojeHora)));
        $dateMenos6Hour = date('Y-m-d H:i:s', strtotime('-6 Hour', strtotime($dateHojeHora)));
        $dateMenos1Minutos = date('Y-m-d H:i:s', strtotime('-1 minutes', strtotime($dateHojeHora)));

        $bus = \App\BusRoute::select('bus.*')
            ->where('linha','=', $linha)
            ->where('created_at','>', $dateMenos1Minutos)
            ->get();



        $busDetails = \App\BusRoute::select(DB::Raw("
           count(*) as qtd,
           sum(velocidade)/count(*) as velocidade_media,
           min(velocidade) as velocidade_minima,
           max(velocidade) as velocidade_maxima 
        "))
            ->groupBy('bus.linha')
            ->where('linha','=', $linha)
            ->where('created_at','>', $dateMenos1Minutos)
            ->first()
            ->toArray();

        $busParados = \App\BusRoute::select(DB::Raw("
           count(*) as qtd_parados
        "))
            ->groupBy('bus.linha')
            ->where('linha','=', $linha)
            ->where('velocidade','=', 0)
            ->where('created_at','>', $dateMenos1Minutos)
            ->first()
            ->toArray();

        $busMovimento = \App\BusRoute::select(DB::Raw("
           count(*) as qtd_movimento
        "))
            ->groupBy('bus.linha')
            ->where('linha','=', $linha)
            ->where('velocidade','>', 0)
            ->where('created_at','>', $dateMenos1Minutos)
            ->first()
            ->toArray();



        //DIA//////////////////////////////////////////////////////////////////////////////
        $busDataLine = \App\BusDataLine::select('bus_data_line.*', DB::Raw("EXTRACT(Day FROM bus_data_line.data) as dia"))
            ->where('bus_data_line.linha', $linha)
            ->where('bus_data_line.data', '>=', $dateMenosTrinta)
            ->where('bus_data_line.data', '<=', $dateHoje)
            ->orderBy('bus_data_line.data')
            ->get();

        $busData = [
            'series' => [
                ['name' => 'Quantidade de Ônibus', 'type' => 'bar', 'data' => []],
                ['name' => 'Velocidade Média (Km)', 'type' => 'area', 'data' => []],
            ],
            'yaxis' =>[
                ['title' => ['text' => 'Quantidade de Ônibus']],
                ['opposite' => true, 'title' => ['text' => 'Velocidade Média (Km)']],
            ],
            'label' => []
        ];

        foreach ($busDataLine as $item) {
            array_push($busData['series'][0]['data'], $item['qtd']);
            array_push($busData['series'][1]['data'], $item['velocidade_media']);

            array_push($busData['label'], $item['dia']);
        }
        //////////////////////////////////////////////////////////////////////////////////
        //MES/////////////////////////////////////////////////////////////////////////////

        $busDataMonthLine = \App\BusDataLine::select(DB::Raw("SUM(bus_data_line.qtd) as qtd"), DB::Raw("AVG(bus_data_line.velocidade_media) as velocidade_media"), DB::Raw("EXTRACT(Month FROM bus_data_line.data) as mes"))
            ->groupBy('mes')
            ->get();

        //bar | line | area
        $busDataMonth = [
            'series' => [
                ['name' => 'Quantidade de Ônibus', 'type' => 'bar', 'data' => []],
                ['name' => 'Velocidade Média (Km)', 'type' => 'area', 'data' => []],
            ],
            'yaxis' =>[
                ['title' => ['text' => 'Quantidade de Ônibus']],
                ['opposite' => true, 'title' => ['text' => 'Velocidade Média (Km)']],
            ],
            'label' => []
        ];

        foreach ($busDataMonthLine as $item) {
            array_push($busDataMonth['series'][0]['data'], $item['qtd']);
            array_push($busDataMonth['series'][1]['data'], $item['velocidade_media']);

            array_push($busDataMonth['label'], $this->MESES_ABREVIADOS[$item['mes']]);
        }
        //////////////////////////////////////////////////////////////////////////////////
        //HORA////////////////////////////////////////////////////////////////////////////
        $today = date('Y-m-d');
        $last7Days = date('Y-m-d', strtotime('-6 days', strtotime($today)));

        $busDataHourLine = \App\BusDataHourLine::select(
            'bus_data_hour_line.*',
            DB::Raw("EXTRACT(Hour FROM bus_data_hour_line.data) as hora"),
            DB::Raw("EXTRACT(dow FROM bus_data_hour_line.data) as diasemana")
        )
            ->where('bus_data_hour_line.linha', $linha)
            ->whereBetween(DB::Raw('DATE(data)'), [$last7Days, $today])
            ->orderBy('hora')
            ->get();

        //$days = [];
        $busDataHour = [];
        foreach ($busDataHourLine as $item) {
            if(!array_key_exists($item->diasemana, $busDataHour)){
                //$day[$item->diasemana] = [];
                $busDataHour[$item->diasemana] = [
                    'series' => [
                        ['name' => 'Quantidade de Ônibus', 'type' => 'bar', 'date' => $item->data, 'data' => []],
                        ['name' => 'Velocidade Média (Km)', 'type' => 'area', 'date' => $item->data, 'data' => []],
                    ],
                    'yaxis' =>[
                        ['title' => ['text' => 'Quantidade de Ônibus']],
                        ['opposite' => true, 'title' => ['text' => 'Velocidade Média (Km)']],
                    ],
                    'label' => []
                ];
            }
            //array_push($days[$item->diasemana], $item);
            //Log::info([$item]);
            array_push($busDataHour[$item->diasemana]['series'][0]['data'], $item['qtd']);
            array_push($busDataHour[$item->diasemana]['series'][1]['data'], $item['velocidade_media']);
            array_push($busDataHour[$item->diasemana]['label'], $item['hora']);

        }

        //////////////////////////////////////////////////////////////////////////////////
        //TEN HOURS/////////////////////////////////////////////////////////////////////////////
        $busDataTenHoursLine = \App\BusDataHourLine::select(
            DB::Raw("EXTRACT(Hour FROM bus_data_hour_line.data) as hora"),
            DB::Raw('sum(qtd) as qtd')
        )
            ->where('bus_data_hour_line.linha', $linha)
            ->where('bus_data_hour_line.data', '>', $dateMenos6Hour)
            ->where('bus_data_hour_line.data', '<=', $dateHojeHora)
            ->orderBy('hora', 'DESC')
            ->groupBy('hora')
            ->get();

        //return $dateHojeHora;


        $busDataTenHours = [
            'series' => [
                ['name' => 'Quantidade de Ônibus', 'data' => []],
            ],
            'label' => []
        ];

        foreach ($busDataTenHoursLine as $item) {
            array_push($busDataTenHours['series'][0]['data'], $item['qtd']);
            array_push($busDataTenHours['label'], $item['hora']);
        }
        ////////////////


        $results = [];

        $results['bus'] = $bus;
        $results['busDetails'] = array_merge($busDetails, $busMovimento, $busParados);
        $results['busData'] = $busData;
        $results['busDataMonth'] = $busDataMonth;
        $results['busDataHour'] = $busDataHour;
        $results['busDataTenHours'] = $busDataTenHours;
        return $results;


    }

    public function getBrt(){


        $dateHojeHora = date('Y-m-d H:i:s');
        $dateMenos6Hour = date('Y-m-d H:i:s', strtotime('-6 Hour', strtotime($dateHojeHora)));
        $last1minute = date('Y-m-d H:i:s', strtotime('-1 minutes', strtotime(date('Y-m-d H:i:s'))));

        $brt = \App\BrtRoute::select('brt.*')
            ->where('created_at', '>', $last1minute)
            ->get();


        $brtDetails = \App\brtRoute::select(DB::Raw("
           count(*) as qtd,
           sum(velocidade)/count(*) as velocidade_media,
           min(velocidade) as velocidade_minima,
           max(velocidade) as velocidade_maxima 
        "))
            ->groupBy('brt.linha')
            ->where('created_at','>', $last1minute)
            ->first()
            ->toArray();

        $brtParados = \App\brtRoute::select(DB::Raw("
           count(*) as qtd_parados
        "))
            ->groupBy('brt.linha')
            ->where('velocidade','=', 0)
            ->where('created_at','>', $last1minute)
            ->first()
            ->toArray();

        $brtMovimento = \App\brtRoute::select(DB::Raw("
           count(*) as qtd_movimento
        "))
            ->groupBy('brt.linha')
            ->where('velocidade','>', 0)
            ->where('created_at','>', $last1minute)
            ->first()
            ->toArray();


        $linhas = \App\Linha::select('linhas.id', 'idiomas_linhas.titulo', 'linhas.slug', 'linhas.imagem', 'linhas.icone')
            ->join('idiomas_linhas', 'linhas.id', '=', 'idiomas_linhas.linha_id')
            ->where('transporte_id', 2)
            ->get();

        $linhasResults = [];

        foreach($linhas as $index => $linha) {

            $estacoes2 = \App\Estacao::select('estacoes.*')
                ->join('linhas_estacoes', 'estacoes.id', '=', 'linhas_estacoes.estacao_id')
                ->where('linhas_estacoes.linha_id', $linha->id)
                ->where('estacoes.transporte_id', 2)
                ->get();

            foreach($estacoes2 as $index2 => $estacao) {

                $linhasResults[$linha->slug]['id'] = $linha->id;
                $linhasResults[$linha->slug]['titulo'] = $linha->titulo;
                $linhasResults[$linha->slug]['slug'] = $linha->slug;
                $linhasResults[$linha->slug]['imagem'] = $linha->imagem;
                $linhasResults[$linha->slug]['icone'] = $linha->icone;

                $linhasResults[$linha->slug]['estacoes']['type'] = 'FeatureCollection';
                $linhasResults[$linha->slug]['estacoes']['features'][$index2]['type'] = 'Feature';
                $linhasResults[$linha->slug]['estacoes']['features'][$index2]['id'] = $index2;
                $linhasResults[$linha->slug]['estacoes']['features'][$index2]['properties']['id'] = $estacao->id;
                $linhasResults[$linha->slug]['estacoes']['features'][$index2]['properties']['titulo'] = $estacao->titulo;
                $linhasResults[$linha->slug]['estacoes']['features'][$index2]['properties']['ativo'] = $estacao->ativo;
                $linhasResults[$linha->slug]['estacoes']['features'][$index2]['properties']['status'] = $estacao->status;
                $linhasResults[$linha->slug]['estacoes']['features'][$index2]['properties']['uf'] = $estacao->uf;
                $linhasResults[$linha->slug]['estacoes']['features'][$index2]['properties']['cod_municipio'] = $estacao->cod_municipio;

                $linhasResults[$linha->slug]['estacoes']['features'][$index2]['geometry']['coordinates'] = [$estacao->longitude, $estacao->latitude];
                $linhasResults[$linha->slug]['estacoes']['features'][$index2]['geometry']['type'] = 'Point';


            }

        }

        $dateHoje = date('Y-m-d');
        $dateMenosTrinta = date('Y-m-d', strtotime('-25 days', strtotime($dateHoje)));


        //DIA//////////////////////////////////////////////////////////////////////////////


        $brtDataLine = \App\BrtDataLine::select(DB::Raw("
           sum(qtd) as qtd,
           sum(velocidade_media)/count(*) as velocidade_media,
           min(velocidade_minima) as velocidade_minima,
           max(velocidade_maxima) as velocidade_maxima,
           EXTRACT(Day FROM brt_data_line.data) as dia
        "))
            ->where('brt_data_line.data', '>=', $dateMenosTrinta)
            ->where('brt_data_line.data', '<=', $dateHoje)
            ->orderBy('brt_data_line.data')
            ->groupBy('brt_data_line.data')
            ->get();

        $brtData = [
            'series' => [
                ['name' => 'Quantidade de BRT', 'type' => 'bar', 'data' => []],
                ['name' => 'Velocidade Média (Km)', 'type' => 'area', 'data' => []],
            ],
            'yaxis' =>[
                ['title' => ['text' => 'Quantidade de BRT'], 'decimais' => 0],
                ['opposite' => true, 'title' => ['text' => 'Velocidade Média (Km)'], 'decimais' => 2],
            ],
            'label' => []
        ];

        foreach ($brtDataLine as $item) {
            array_push($brtData['series'][0]['data'], $item['qtd']);
            array_push($brtData['series'][1]['data'], $item['velocidade_media']);

            array_push($brtData['label'], $item['dia']);
        }
        //MES/////////////////////////////////////////////////////////////////////////////

        $brtDataMonthLine = \App\BrtDataLine::select(DB::Raw("SUM(brt_data_line.qtd) as qtd"), DB::Raw("AVG(brt_data_line.velocidade_media) as velocidade_media"), DB::Raw("EXTRACT(Month FROM brt_data_line.data) as mes"))
            ->groupBy('mes')
            ->get();

        //bar | line | area
        $brtDataMonth = [
            'series' => [
                ['name' => 'Quantidade de BRT', 'type' => 'bar', 'data' => []],
                ['name' => 'Velocidade Média (Km)', 'type' => 'area', 'data' => []],
            ],
            'yaxis' =>[
                ['title' => ['text' => 'Quantidade de BRT'], 'decimais' => 0],
                ['opposite' => true, 'title' => ['text' => 'Velocidade Média (Km)'], 'decimais' => 2],
            ],
            'label' => []
        ];

        foreach ($brtDataMonthLine as $item) {
            array_push($brtDataMonth['series'][0]['data'], $item['qtd']);
            array_push($brtDataMonth['series'][1]['data'], $item['velocidade_media']);

            array_push($brtDataMonth['label'], $this->MESES_ABREVIADOS[$item['mes']]);
        }
        //////////////////////////////////////////////////////////////////////////////////
        //HORA////////////////////////////////////////////////////////////////////////////
        $today = date('Y-m-d');
        $last7Days = date('Y-m-d', strtotime('-6 days', strtotime($today)));

        $brtDataHourLine = \App\BrtDataHourLine::select(
            DB::Raw("sum(velocidade_media)/count(*) as velocidade_media"),
            DB::Raw("EXTRACT(Hour FROM brt_data_hour_line.data) as hora"),
            DB::Raw("EXTRACT(dow FROM brt_data_hour_line.data) as diasemana"),
            DB::Raw("EXTRACT(day FROM brt_data_hour_line.data) as date"),
            DB::Raw('sum(brt_data_hour_line.qtd) as qtd')
        )

            ->whereBetween(DB::Raw('DATE(data)'), [$last7Days, $today])
            ->orderBy('hora')
            ->groupBy('data')
            ->get();

        Log::info($today);

        //$days = [];
        $brtDataHour = [];
        foreach ($brtDataHourLine as $item) {
            if(!array_key_exists($item->diasemana, $brtDataHour)){
                //$day[$item->diasemana] = [];
                $brtDataHour[$item->diasemana] = [
                    'series' => [
                        ['name' => 'Quantidade de BRT', 'type' => 'bar', 'date' => $item->data, 'data' => []],
                        ['name' => 'Velocidade Média (Km)', 'type' => 'area', 'date' => $item->data, 'data' => []],
                    ],
                    'yaxis' =>[
                        ['title' => ['text' => 'Quantidade de BRT'], 'decimais' => 0],
                        ['opposite' => true, 'title' => ['text' => 'Velocidade Média (Km)'], 'decimais' => 2],
                    ],
                    'label' => []
                ];
            }

            array_push($brtDataHour[$item->diasemana]['series'][0]['data'], $item['qtd']);
            array_push($brtDataHour[$item->diasemana]['series'][1]['data'], $item['velocidade_media']);
            array_push($brtDataHour[$item->diasemana]['label'], $item['hora']);

        }

        //////////////////////////////////////////////////////////////////////////////////
        //TEN HOURS/////////////////////////////////////////////////////////////////////////////
        $brtDataTenHoursLine = \App\BrtDataHourLine::select(
            DB::Raw("EXTRACT(Hour FROM brt_data_hour_line.data) as hora"),
            DB::Raw('sum(qtd) as qtd')
        )
            ->where('brt_data_hour_line.data', '>', $dateMenos6Hour)
            ->where('brt_data_hour_line.data', '<=', $dateHojeHora)
            ->orderBy('hora', 'DESC')
            ->groupBy('hora')
            ->get();

        //return $dateHojeHora;


        $brtDataTenHours = [
            'series' => [
                ['name' => 'Quantidade de BRT', 'data' => []],
            ],
            'label' => []
        ];

        foreach ($brtDataTenHoursLine as $item) {
            array_push($brtDataTenHours['series'][0]['data'], $item['qtd']);
            array_push($brtDataTenHours['label'], $item['hora']);
        }
        ////////////////


        $results = [];

        $results['brt'] = $brt;
        $results['busDetails'] = array_merge($brtDetails, $brtMovimento, $brtParados);
        $results['linhas'] = $linhasResults;
        $results['brtData'] = $brtData;
        $results['brtDataMonth'] = $brtDataMonth;
        $results['brtDataHour'] = $brtDataHour;
        $results['brtDataTenHours'] = $brtDataTenHours;
        return $results;



    }
    public function getBrtTESTE(){

        $brt = \App\BrtRoute::select('brt.*')
            ->get();

        $estacoes = \App\Estacao::select('estacoes.*')
            ->where('transporte_id', 6)
            ->get();

        $linhas = \App\Linha::select('linhas.*')
            ->join('linhas_estacoes', 'linhas.id', '=', 'linhas_estacoes.linha_id')
            ->join('estacoes', 'transportes.id', '=', 'idiomas_transportes.transporte_id')
            ->get();


        Log::info([$linhas]);



    }

    public function getBrtEstacoes(){
        $pagina = "https://opendata.arcgis.com/datasets/29cc383d2d344e8387dda153ec0d545d_10.geojson";

        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_URL, $pagina );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        $result = curl_exec( $ch );
        curl_close( $ch );

        return json_decode($result, true);
    }

    public function getStations(){


        $estacoes = \App\Estacao::select('estacoes.*', 'transportes.slug', 'idiomas_transportes.titulo as transporte', 'transportes.imagem as img', 'transportes.icone', 'transportes.tipo')
            ->join('transportes', 'transportes.id', '=', 'estacoes.transporte_id')
            ->join('idiomas_transportes', 'transportes.id', '=', 'idiomas_transportes.transporte_id')
            ->get();

        $results = [];
        $cont = [];
        foreach($estacoes as $index => $valor){

            if(!array_key_exists($valor->slug, $cont)){
                $cont[$valor->slug] = -1;
            }

            $cont[$valor->slug]++;

            $results[$valor->slug]['type'] = 'FeatureCollection';
            $results[$valor->slug]['features'][$cont[$valor->slug]]['type'] = 'Feature';
            $results[$valor->slug]['features'][$cont[$valor->slug]]['id'] = $cont[$valor->slug];
            $results[$valor->slug]['features'][$cont[$valor->slug]]['properties']['titulo'] = $valor->titulo;
            $results[$valor->slug]['features'][$cont[$valor->slug]]['properties']['subtitulo'] = $valor->subtitulo;
            $results[$valor->slug]['features'][$cont[$valor->slug]]['properties']['resumida'] = $valor->resumida;
            $results[$valor->slug]['features'][$cont[$valor->slug]]['properties']['descricao'] = $valor->descricao;
            $results[$valor->slug]['features'][$cont[$valor->slug]]['properties']['data_inicio'] = $valor->data_inicio;
            $results[$valor->slug]['features'][$cont[$valor->slug]]['properties']['data_termino'] = $valor->data_termino;
            $results[$valor->slug]['features'][$cont[$valor->slug]]['properties']['endereco'] = $valor->endereco;
            $results[$valor->slug]['features'][$cont[$valor->slug]]['properties']['telefone'] = $valor->telefone;
            $results[$valor->slug]['features'][$cont[$valor->slug]]['properties']['ativo'] = $valor->ativo;
            $results[$valor->slug]['features'][$cont[$valor->slug]]['properties']['status'] = $valor->status;
            $results[$valor->slug]['features'][$cont[$valor->slug]]['properties']['uf'] = $valor->uf;
            $results[$valor->slug]['features'][$cont[$valor->slug]]['properties']['cod_municipio'] = $valor->cod_municipio;

            $integracoes = \App\Transporte::select('transportes.id', 'transportes.titulo', 'transportes.slug')
                ->join('integracoes', 'transportes.id', '=', 'integracoes.transporte_id')
                ->where('integracoes.estacao_id', '=', $valor->id)
                ->get();

            foreach($integracoes as $integracao) {
                $results[$valor->slug]['features'][$cont[$valor->slug]]['properties'][$integracao->slug] = 1;
            }

            $results[$valor->slug]['features'][$cont[$valor->slug]]['geometry']['coordinates'] = [$valor->longitude, $valor->latitude];
            $results[$valor->slug]['features'][$cont[$valor->slug]]['geometry']['type'] = 'Point';

            $results[$valor->slug]['properties']['titulo'] = $valor->transporte;
            $results[$valor->slug]['properties']['imagem'] = $valor->img;
            $results[$valor->slug]['properties']['icone'] = $valor->icone;
            $results[$valor->slug]['properties']['tipo'] = $this->TIPOS_TRANSPORTES[$valor->tipo];
        }
        
        

        return [$results];

    }

    public function getStationsEstacoes(){
        $pagina = "https://opendata.arcgis.com/datasets/29cc383d2d344e8387dda153ec0d545d_10.geojson";

        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_URL, $pagina );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        $result = curl_exec( $ch );
        curl_close( $ch );

        return json_decode($result, true);
    }

    public function getRadar(){

        $radares = \App\RadaresRoute::select('dws_radar.*', 'radares.slug', 'radares.imagem as img', 'radares.icone', 'radares.tipo', 'idiomas_radares.titulo')
            ->join('radares', 'radares.id', '=', 'dws_radar.tipo')
            ->join('idiomas_radares', 'radares.id', '=', 'idiomas_radares.radar_id')
            ->get();

        $results = [];
        $cont = [];
        foreach($radares as $index => $valor){

            if(!array_key_exists($valor->slug, $cont)){
                $cont[$valor->slug] = -1;
            }

            $cont[$valor->slug]++;

            $results[$valor->slug]['type'] = 'FeatureCollection';
            $results[$valor->slug]['features'][$cont[$valor->slug]]['type'] = 'Feature';
            $results[$valor->slug]['features'][$cont[$valor->slug]]['id'] = $cont[$valor->slug];
            $results[$valor->slug]['features'][$cont[$valor->slug]]['properties']['titulo'] = $valor->titulo;
            $results[$valor->slug]['features'][$cont[$valor->slug]]['properties']['tipo'] = $valor->tipo;
            $results[$valor->slug]['features'][$cont[$valor->slug]]['properties']['velocidade'] = $valor->velocidade;
            $results[$valor->slug]['features'][$cont[$valor->slug]]['properties']['sentido_duplo'] = $valor->sentido_duplo;
            $results[$valor->slug]['features'][$cont[$valor->slug]]['properties']['sentido_todos'] = $valor->sentido_todos;
            $results[$valor->slug]['features'][$cont[$valor->slug]]['properties']['direcao_real'] = $valor->direcao_real;
            $results[$valor->slug]['features'][$cont[$valor->slug]]['properties']['sigla_rodovia'] = $valor->sigla_rodovia;
            $results[$valor->slug]['features'][$cont[$valor->slug]]['properties']['km_rodovia'] = $valor->km_rodovia;
            $results[$valor->slug]['features'][$cont[$valor->slug]]['properties']['pais'] = $valor->pais;
            $results[$valor->slug]['features'][$cont[$valor->slug]]['properties']['status'] = $valor->status;
            $results[$valor->slug]['features'][$cont[$valor->slug]]['properties']['uf'] = $valor->uf;
            $results[$valor->slug]['features'][$cont[$valor->slug]]['properties']['cod_municipio'] = $valor->cod_municipio;


            $results[$valor->slug]['features'][$cont[$valor->slug]]['geometry']['coordinates'] = [$valor->longitude, $valor->latitude];
            $results[$valor->slug]['features'][$cont[$valor->slug]]['geometry']['type'] = 'Point';

            $results[$valor->slug]['properties']['titulo'] = $valor->titulo;
            $results[$valor->slug]['properties']['imagem'] = $valor->img;
            $results[$valor->slug]['properties']['icone'] = $valor->icone;
            $results[$valor->slug]['properties']['icone'] = $valor->icone;
            $results[$valor->slug]['properties']['tipo'] = $this->TIPOS_TRANSPORTES[$valor->tipo];
        }

        $radaresGeral = \App\RadaresRoute::select(DB::Raw("
           velocidade,
           count(*) as qtd
        "))
            ->where('velocidade', '>', 0)
            ->groupBy('velocidade')
            ->skip(1)
            ->take(24)
            ->get();

        return [$results, $radaresGeral];

    }

    public function getRadarANTIGO(){  

        $results = [];
        $results['radar'] = json_decode(file_get_contents('http://mapa.maparadar.com/ListaRadares3.aspx?Bounds=-30.963537,-72.21499,3.836783,-31.609521&RadaresTela=500&RadaresNormais=1&TiposExibidos=1,2,4,5,6,7,9,10,11'));
        $results['estacoes'] = json_decode(file_get_contents('https://opendata.arcgis.com/datasets/f753941f44a749d4987f1111aa6486b3_22.geojson'));

        return $results;


    }

    

    public function getRadarEstacoes(){
        $pagina = "https://opendata.arcgis.com/datasets/29cc383d2d344e8387dda153ec0d545d_10.geojson";

        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_URL, $pagina );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        $result = curl_exec( $ch );
        curl_close( $ch );

        return json_decode($result, true);
    }

    public function getMetro(){


        $pagina = "https://opendata.arcgis.com/datasets/7a0b22723c5a458faaae79f046163504_19.geojson";

        $ch = curl_init();

        curl_setopt( $ch, CURLOPT_URL, $pagina );

        // define que o conteúdo obtido deve ser retornado em vez de exibido
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );

        $result = curl_exec( $ch );

        curl_close( $ch );


        return json_decode($result, true);


    }

    public function estacao(){
        return view('transporte.estacoes');
    }

    /*$results['metro'] = json_decode(file_get_contents('https://opendata.arcgis.com/datasets/7a0b22723c5a458faaae79f046163504_19.geojson'));
        $results['brt'] = json_decode(file_get_contents('https://opendata.arcgis.com/datasets/f753941f44a749d4987f1111aa6486b3_22.geojson'));
        $results['vlt'] = json_decode(file_get_contents('https://opendata.arcgis.com/datasets/29cc383d2d344e8387dda153ec0d545d_10.geojson'));
        $results['trem'] = json_decode(file_get_contents('https://opendata.arcgis.com/datasets/8c9f264be1e946b1b49cf4c198bd5e46_16.geojson'));
        $results['bonde'] = json_decode(file_get_contents('https://opendata.arcgis.com/datasets/b424e9356bd246e89d0e12deedf88536_12.geojson'));
        $results['teleferico'] = json_decode(file_get_contents('https://opendata.arcgis.com/datasets/000eaa25775b4227a4cd93716d27429a_6.geojson'));
        $results['barca'] = json_decode(file_get_contents('https://opendata.arcgis.com/datasets/a1ca34c5510342cf9b056c6aaeb341c8_1.geojson'));
        $results['aeroporto'] = json_decode(file_get_contents('https://opendata.arcgis.com/datasets/7876913025c445048c44099ac4c11df7_3.geojson'));
        $results['bicicletario'] = json_decode(file_get_contents('https://opendata.arcgis.com/datasets/55d5fde287144084ba9d0c17c3d99dad_0.geojson'));*/
}
