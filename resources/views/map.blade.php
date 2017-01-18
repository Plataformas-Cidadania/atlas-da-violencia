@extends('layout')
@section('title', 'Mapa')
@section('content')
    <style>
        #mapid { width: 100%; height: 600px; }

        .legend {
            line-height: 18px;
            color: #555;
        }

        .legend i {
            width: 15px;
            height: 15px;
            float: left;
            margin-right: 8px;
            opacity: 0.7;
        }

        .info {
            padding: 6px 8px;
            font: 14px/16px Arial, Helvetica, sans-serif;
            background: white;
            background: rgba(255,255,255,0.8);
            box-shadow: 0 0 15px rgba(0,0,0,0.2);
            border-radius: 5px;
            width: 150px;
        }
        .info h4 {
            margin: 0 0 5px;
            color: #777;
        }

    </style>
    {{--@if(substr($base_href, 0,9)=='evbsb1052')--}}
        <style>
            .irs-slider {
                background: url(http://evbsb1052.ipea.gov.br/atlasviolencia/img/sprite-skin-flat.png) repeat-x;
            }
        </style>
    {{--@endif--}}

    <div class="container">

        <div id="pgSerie"></div>



        {{--<div class="hidden-print">
            <h4><i class="fa fa-calendar" aria-hidden="true"></i> Periodicidade</h4>
            <input type="text" id="range" value=""  name="range" ng-model="range" />
        </div>--}}
        {{--<br><br>
        <div id="mapid"></div>
        <br><br>
        <div id="listValoresSeries"></div>
        <canvas id="myChart" width="400" height="200"></canvas>--}}
        {{--<canvas id="myChartRadar" width="400" height="200"></canvas>--}}
        {{--<button onclick="getData()">Carregar</button>--}}
    </div>


@endsection