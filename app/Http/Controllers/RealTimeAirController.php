<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RealTimeController extends Controller
{
    public function saveBrt(){
        //$data = file_get_contents("http://dadosabertos.rio.rj.gov.br/apiTransporte/apresentacao/rest/index.cfm/obterPosicoesDaLinha/409");

        //$pagina = 'http://dadosabertos.rio.rj.gov.br/apiTransporte/apresentacao/rest/index.cfm/obterPosicoesDaLinha/409';

        $pagina = 'https://data.rb24.com/live?airline=&station=&aircraft=&airport=SBBR&fn=&far=&fms=&flightid=&timestamp=1573828997039&bounds=-1.843,0.374,-25.127,-100.467&zoom=4.7382865673883865&designator=iata&ff=false&os=web&adsb=true&asdi=true&ocea=true&mlat=true&sate=true&uat=true&hfdl=true&esti=true&diverted=false&delayed=false&isga=false';
        //$pagina = 'https://data.rb24.com/live?airline=TAM,GLO&station=&aircraft=&airport=&fn=&far=&fms=&flightid=&timestamp=1573831293713&bounds=70.506,180,-44.252,-180&zoom=2.0986089724195964&designator=iata&ff=false&os=web&adsb=true&asdi=true&ocea=true&mlat=true&sate=true&uat=true&hfdl=true&esti=true&diverted=false&delayed=false&isga=false';

        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_URL, $pagina );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $retorno = curl_exec( $ch );
        curl_close( $ch );

        $object = \GuzzleHttp\json_decode($retorno);



        foreach($object->DATA as $item){
            if($item[0] != 0){
                //$date = substr($item[0], 0, 10);
                //$date = "2019-03-01";
                //$time = substr($item[0], 11, 8);

                //$data['data_hora'] = $date." ".$time;
                $data['voo'] = $item[0];
                $data['latitude'] = $item[1];
                $data['longitude'] = $item[2];
                $data['autitude'] = $item[4];
                $data['aeronave_modelo'] = $item[5];
                $data['velocidade'] = $item[6];
                $data['sistema'] = $item[8];
                $data['aeronave_prefixo'] = $item[9];
                $data['local_saida'] = $item[10];
                $data['local_chegada'] = $item[11];
                $data['linha_aerea'] = $item[12];
                $data['linha'] = $item->linha;
                $data['data_hora'] = [];
                $data['id_migracao_trajeto'] = $item->id_migracao_trajeto;
                $data['sentido'] = $item->sentido;
                $data['trajeto'] = $item->trajeto;
                $data['uf'] = 33;
                $data['cod_municipio'] = 3324121;
                $data['cmsuser_id'] = 1;

                \App\AirRoute::create($data);

            }

        }


        //return $object->COLUMNS['codigo'];
        return 'Gravado com sucesso :)';

    }

    public function brtRoute($line, $order = null){

        $route = [];

        $whereOrder = [];
        if($order!=null){
            $whereOrder = [['ordem', $order]];
        }

        $total = \App\BrtRoute::select('latitude', 'longitude')
            ->where('linha', $line)
            ->when($order!=null, function($query) use ($whereOrder){
                return $query->where($whereOrder);
            })
            ->count();

        //Log::info($total);

        $first = \App\BrtRoute::select('latitude', 'longitude')
            ->where('linha', $line)
            ->when($order!=null, function($query) use ($whereOrder){
                return $query->where($whereOrder);
            })
            ->first();

        //Log::info($first);

        $latitude = $first->latitude;
        $longitude = $first->longitude;
        $latitudeAnterior = $first->latitude;
        $longitudeAnterior = $first->longitude;
        array_push($route, [$latitude, $longitude]);

        for($i = 0; $i < $total-1; $i++){
            $next = \App\BrtRoute::select('latitude', 'longitude', DB::Raw("(POW((longitude - $longitude),2) + POW((latitude - $latitude),2)) AS distance"))
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

    public function brtDateHour(){

        $dateHojeHora = date('Y-m-d H:i:s');
        $dateMenos60Minutos = date('Y-m-d H:i:s', strtotime('-60 minutes', strtotime($dateHojeHora)));

        $brt = \App\BrtRoute::select('codigo', DB::Raw('sum(velocidade) as soma_velocidade'))
            ->where('created_at', '>', $dateMenos60Minutos)
            ->groupBy('codigo')
            ->get();

        $ativos = [];
        foreach ($brt as $b) {
            if($b->soma_velocidade > 0){
                array_push($ativos, $b->codigo);
            }
        }
        $ativos_str = "'".implode("','", $ativos)."'";

        $rowsBrt = \App\BrtRoute::select(DB::Raw("
           linha,           
           sum(velocidade)/count(*) as velocidade_media,
           min(velocidade) as velocidade_minima,
           max(velocidade) as velocidade_maxima  
        "))
            ->whereIn('codigo', $ativos)
            ->where('created_at', '>', $dateMenos60Minutos)
            ->groupBy('linha')
            ->get();

        $qtdsOrders = DB::select("
            select linha, count(codigo) as qtd
            from (select distinct linha, codigo from brt where codigo in ($ativos_str) and created_at > '$dateMenos60Minutos') 
            as brt         
            group by linha
        ");

        //return $qtdsOrders;

        $dateHojeHora = date('Y-m-d H:i:s');

        foreach ($rowsBrt as $item) {
            foreach ($qtdsOrders as $item2) {
                if($item->linha === $item2->linha){
                    $item->qtd = $item2->qtd;
                }
            }
            \App\BrtDataHourLine::create([
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
        \App\BrtRoute::where('created_at', '<', $dateMenos61Minutos)->delete();

        Log::info('brtDateHour');

        return ':)';
        


    }

    public function brtDate(){

        $dateHojeHora = date('Y-m-d H:i:s');
        $dateMenos60Minutos = date('Y-m-d H:i:s', strtotime('-1440 minutes', strtotime($dateHojeHora)));

        $rowsBrt = \App\BrtDataHourLine::select(DB::Raw("
           linha,
           max(qtd) as qtd,
           sum(velocidade_media)/count(*) as velocidade_media,
           min(velocidade_minima) as velocidade_minima,
           max(velocidade_maxima) as velocidade_maxima  
        "))
            ->where('created_at', '>', $dateMenos60Minutos)
            ->groupBy('linha')
            ->skip(1)
            ->take(24)
            ->get();

        $dateHoje = date('Y-m-d');



        foreach ($rowsBrt as $item) {
            \App\BrtDataLine::create([
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
        \App\BrtDataHourLine::where('created_at', '<', $dateMenos9dias)->delete();


        Log::info('brtDate');

        return ':)';


        /*$qtdBrt = \App\BrtRoute::select('linha')->count();
        $qtdBrtLinha = \App\BrtRoute::select('linha')->count();

        $totalVelocity = \App\BrtRoute::select('velocidade')->sum('velocidade');
        $velocityMin = \App\BrtRoute::select('velocidade')->where('velocidade', '>', 0)->min('velocidade');
        $velocityMax = \App\BrtRoute::select('velocidade')->max('velocidade');

        $totalVelocityLine = \App\BrtRoute::select('velocidade')->sum('velocidade');
        $velocityLineMin = \App\BrtRoute::select('velocidade')->where('velocidade', '>', 0)->min('velocidade');
        $velocityLineMax = \App\BrtRoute::select('velocidade')->max('velocidade');

        $velocityMedia = $totalVelocity/$qtdBrt;
        $velocityMediLinea = $totalVelocityLine/$qtdBrtLinha;

        $dateHoje = date('Y-m-d');

        \App\BrtDataLine::create([
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

        $dateHojeHora = date('Y-m-d H:i:s');
        $dateMenos60Minutos = date('Y-m-d H:i:s', strtotime('-60 minutes', strtotime($dateHojeHora)));


        $rowsBrt = \App\BrtRoute::select(DB::Raw("
               linha,
               count(*) as qtd,
               sum(velocidade)/count(*) as velocidade_media,
               min(velocidade) as velocidade_minima,
               max(velocidade) as velocidade_maxima
            "))
            ->where('created_at', '>', $dateMenos60Minutos)
            ->where('velocidade', '>', 0)
            ->groupBy('linha')
            ->skip(1)
            ->take(60)
            ->get();

        foreach ($rowsBrt as $item) {
            \App\BrtDataHourLine::create([
                'id_transporte' => '2',
                'linha' => $item->linha,
                'qtd' => $item->qtd,
                'velocidade_minima' => $item->velocidade_minima,
                'velocidade_maxima' => $item->velocidade_maxima,
                'velocidade_media' => number_format($item->velocidade_media, 2),
                'data' => $dateHojeHora,
                'uf' => 33,
                'cod_municipio' => 3324121,
                'cmsuser_id' => 1 ]);
        }

        return $rowsBrt;
    }
}


