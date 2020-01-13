<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BusController extends Controller
{
    public function saveBusRoute(){
        //$data = file_get_contents("http://dadosabertos.rio.rj.gov.br/apiTransporte/apresentacao/rest/index.cfm/obterPosicoesDaLinha/409");

        //$pagina = 'http://dadosabertos.rio.rj.gov.br/apiTransporte/apresentacao/rest/index.cfm/obterPosicoesDaLinha/409';
        $pagina = 'https://dadosabertos.rio.rj.gov.br/apiTransporte/apresentacao/rest/index.cfm/obterTodasPosicoes';


        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_URL, $pagina );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $retorno = curl_exec( $ch );
        curl_close( $ch );

        $object = \GuzzleHttp\json_decode($retorno);

        foreach($object->DATA as $item){
            if($item[2] != '') {
                //$date = substr($item[0], 0, 10);
                //$date = "2019-03-01";
                //$time = substr($item[0], 11, 8);

                //$data['data_hora'] = $date." ".$time;
                $data['data_hora'] = $item[0];
                $data['ordem'] = $item[1];
                $data['linha'] = $item[2];
                $data['latitude'] = $item[3];
                $data['longitude'] = $item[4];
                $data['velocidade'] = $item[5];
                $data['uf'] = 33;
                $data['cod_municipio'] = 3324121;
                $data['cmsuser_id'] = 1;

                \App\BusRoute::create($data);
            }
        }

        return $object->COLUMNS[0];

    }

    public function busRoute($line, $order = null){

        $route = [];

        $whereOrder = [];
        if($order!=null){
            $whereOrder = [['ordem', $order]];
        }

        $total = \App\BusRoute::select('latitude', 'longitude')
            ->where('linha', $line)
            ->when($order!=null, function($query) use ($whereOrder){
                return $query->where($whereOrder);
            })
            ->count();

        $first = \App\BusRoute::select('latitude', 'longitude')
            ->where('linha', $line)
            ->when($order!=null, function($query) use ($whereOrder){
                return $query->where($whereOrder);
            })
            ->first();

        $latitude = $first->latitude;
        $longitude = $first->longitude;
        $latitudeAnterior = $first->latitude;
        $longitudeAnterior = $first->longitude;
        array_push($route, [$latitude, $longitude]);

        for($i = 0; $i < $total-1; $i++){
            $next = \App\BusRoute::select('latitude', 'longitude', DB::Raw("(POW((longitude - $longitude),2) + POW((latitude - $latitude),2)) AS distance"))
                ->distinct()
                ->where('linha', $line)
                ->where('longitude', '!=', $longitude)
                ->where('latitude', '!=', $latitude)
                ->where('longitude', '!=', $longitudeAnterior)
                ->where('latitude', '!=', $latitudeAnterior)
                ->when($order!=null, function($query) use ($whereOrder){
                    return $query->where($whereOrder);
                })
                ->orderBy('distance')
                ->first();

            $latitudeAnterior = $latitude;
            $longitudeAnterior = $longitude;
            $latitude = $next->latitude;
            $longitude = $next->longitude;
            array_push($route, [$latitude, $longitude]);
        }

        return $route;
    }

    public function busDateHour(){

        $dateHojeHora = date('Y-m-d H:i:s');
        $dateMenos60Minutos = date('Y-m-d H:i:s', strtotime('-60 minutes', strtotime($dateHojeHora)));

        $bus = \App\BusRoute::select('ordem', DB::Raw('sum(velocidade) as soma_velocidade'))
            ->where('created_at', '>', $dateMenos60Minutos)
            ->groupBy('ordem')
            ->get();

        $ativos = [];
        foreach ($bus as $b) {
            if($b->soma_velocidade > 0){
                array_push($ativos, $b->ordem);
            }
        }
        $ativos_str = "'".implode("','", $ativos)."'";

        $rowsBus = \App\BusRoute::select(DB::Raw("
           linha,
           sum(velocidade)/count(*) as velocidade_media,
           min(velocidade) as velocidade_minima,
           max(velocidade) as velocidade_maxima
        "))
            ->whereIn('ordem', $ativos)
            ->where('created_at', '>', $dateMenos60Minutos)
            ->groupBy('linha')
            ->get();

        $qtdsOrders = DB::select("
            select linha, count(ordem) as qtd
            from (select distinct linha, ordem from bus where ordem in ($ativos_str) and created_at > '$dateMenos60Minutos') 
            as bus         
            group by linha
        ");


        $dateHojeHora = date('Y-m-d H:i:s');

        foreach ($rowsBus as $item) {
            foreach ($qtdsOrders as $item2) {
                if($item->linha === $item2->linha){
                    $item->qtd = $item2->qtd;
                }
            }
            \App\BusDataHourLine::create([
                'id_transporte' => '2',
                'linha' => $item->linha,
                'qtd' => $item->qtd,
                'velocidade_minima' => number_format($item->velocidade_minima, 2),
                'velocidade_maxima' => number_format($item->velocidade_maxima, 2),
                'velocidade_media' => number_format($item->velocidade_media, 2),
                'data' => $dateHojeHora,
                'uf' => 33,
                'cod_municipio' => 3324121,
                'cmsuser_id' => 1 ]);
        }


        $dateMenos61Minutos = date('Y-m-d H:i:s', strtotime('-61 minutes', strtotime($dateHojeHora)));
        \App\BusRoute::where('created_at', '<', $dateMenos61Minutos)->delete();

        Log::info('busDateHour');

        return ':)';


    }

    public function busDate(){

        $dateHojeHora = date('Y-m-d H:i:s');
        $dateMenos60Minutos = date('Y-m-d H:i:s', strtotime('-1440 minutes', strtotime($dateHojeHora)));

        $rowsBus = \App\BusDataHourLine::select(DB::Raw("
           linha,
           max(qtd) as qtd,
           sum(velocidade_media)/count(*) as velocidade_media,
           min(velocidade_minima) as velocidade_minima,
           max(velocidade_maxima) as velocidade_maxima 
        "))
            /*->orderBy('id', 'desc')*/
            ->where('created_at', '>', $dateMenos60Minutos)
            ->groupBy('linha')
            ->skip(1)
            ->get();

        $dateHoje = date('Y-m-d');

        foreach ($rowsBus as $item) {
            \App\BusDataLine::create([
                'id_transporte' => '2',
                'linha' => $item->linha,
                'qtd' => $item->qtd,
                'velocidade_minima' => number_format($item->velocidade_minima, 2),
                'velocidade_maxima' => number_format($item->velocidade_maxima, 2),
                'velocidade_media' => number_format($item->velocidade_media, 2),
                'data' => $dateHoje,
                'uf' => 33,
                'cod_municipio' => 3324121,
                'cmsuser_id' => 1 ]);
        }

        $dateMenos9dias = date('Y-m-d H:i:s', strtotime('-9 days', strtotime($dateHojeHora)));
        \App\BusDataHourLine::where('created_at', '<', $dateMenos9dias)->delete();

        Log::info('busDate');

        return ':)';
        

        /*$dataHourLine = \App\BusDataHourLine::where()->get();





        $qtdBus = \App\BusRoute::select('linha')->count();

        $qtdBusLinha = \App\BusRoute::select('linha')->where('linha', '=', 409)->count();

        $totalVelocity = \App\BusRoute::select('velocidade')->sum('velocidade');
        $velocityMin = \App\BusRoute::select('velocidade')->where('velocidade', '>', 0)->min('velocidade');
        $velocityMax = \App\BusRoute::select('velocidade')->max('velocidade');

        $totalVelocityLine = \App\BusRoute::select('velocidade')->where('linha', '=', 409)->sum('velocidade');
        $velocityLineMin = \App\BusRoute::select('velocidade')->where('velocidade', '>', 0)->where('linha', '=', 409)->min('velocidade');
        $velocityLineMax = \App\BusRoute::select('velocidade')->where('linha', '=', 409)->max('velocidade');

        $velocityMedia = $totalVelocity/$qtdBus;
        $velocityMediLinea = $totalVelocityLine/$qtdBusLinha;

        $dateHoje = date('Y-m-d');

        \App\BusDataLine::create([
            'id_transporte' => '2',
            'linha' => 409,
            'qtd' => 45,
            'velocidade_minima' => $velocityLineMin,
            'velocidade_maxima' => $velocityLineMax,
            'velocidade_media' => $velocityMediLinea,
            'data' => $dateHoje,
            'uf' => 33,
            'cod_municipio' => 3324121,
            'cmsuser_id' => 1 ]);

        return ':)';*/

    }

    public function teste(){
        $rowsBus = \App\BusRoute::select(DB::Raw("
           linha,
           count(*) as qtd,
           sum(velocidade)/count(*) as velocidade_media,
           min(velocidade) as velocidade_minima,
           max(velocidade) as velocidade_maxima,
           INTERVAL '60 minutes'    
        "))
            /*->orderBy('id', 'desc')*/
            ->groupBy('linha')
            ->skip(1)
            ->take(60)
            ->get();

        return $rowsBus;
    }

    public function testeCron(){
        Log::info('Teste Cron');
    }
}


