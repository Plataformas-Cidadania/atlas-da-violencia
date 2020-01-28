<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RadaresController extends Controller
{
    public function saveRadares(){

        //$pagina = 'http://mapa.maparadar.com/ListaRadares3.aspx?Bounds=-30.963537,-72.21499,3.836783,-31.609521&RadaresTela=45296&RadaresNormais=1&TiposExibidos=1,2,3,4,5,6,7,8,9';
        $pagina = 'http://mapa.maparadar.com/ListaRadares3.aspx?Bounds=-30.963537,-72.21499,3.836783,-31.609521&RadaresTela=7929&RadaresNormais=1&TiposExibidos=1,2,3,4,5,6,7,8,9';


        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_URL, $pagina );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $retorno = curl_exec( $ch );
        curl_close( $ch );

        $object = \GuzzleHttp\json_decode($retorno);

        //return gettype($object[0]->Ativo);



        foreach($object as $item){
            Log::info([$item]);
            if($item->TipoRadarID != 0){

                $data['tipo'] = $item->TipoRadarID;
                $data['latitude'] = $item->Latitude;
                $data['longitude'] = $item->Longitude;
                $data['data_hora'] = substr($item->DataAlteracao, 6, -2);
                $data['velocidade'] = $item->VelocidadeVia;
                $data['radar_id_fonte'] = $item->RadarID;
                $data['km_rodovia'] = $item->KmRodovia;
                $data['sigla_rodovia'] = $item->SiglaRodovia;
                $data['sentido_duplo'] = $item->SentidoDuplo;
                $data['sentido_todos'] = ($item->SentidoTodos == true ? 1 : 0);
                $data['direcao_real'] = $item->DirecaoReal;
                $data['pais'] = $item->Pais;
                $data['uf'] = '';
                $data['cod_municipio'] = '';
                $data['status'] = $item->Ativo;
                $data['cmsuser_id'] = 1;

                Log::info($item->Ativo);

                \App\RadaresRoute::create($data);

            }
        }

        return ":)";

    }






}


