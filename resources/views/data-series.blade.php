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
            opacity: 1;
        }

        .info {
            padding: 6px 8px;
            font: 14px/16px Arial, Helvetica, sans-serif;
            background: white;
            background: rgba(255,255,255,0.8);
            box-shadow: 0 0 15px rgba(0,0,0,0.2);
            border-radius: 5px;
            width: 230px;
        }
        .info h4 {
            margin: 0 0 5px;
            color: #777;
        }

        .icons-groups{
            float:right;
            background-image: url(img/icons/png/icons-groups.png);
            width: 52px;
            height: 52px;
            cursor: pointer;
        }
        .icon-group-map{
            background-position: 0 0;
        }
        .icon-group-map-disable{
            background-position: 0 -52px;
        }
        .icon-group-chart{
            background-position: -52px 0;
        }
        .icon-group-chart-disable{
            background-position: -52px -52px;
        }
        .icon-group-table{
            background-position: -104px 0;
        }
        .icon-group-table-disable{
            background-position: -104px -52px;
        }
        .icon-group-rate{
            background-position: -156px 0;
        }
        .icon-group-rate-disable{
            background-position: -156px -52px;
        }
        .icon-group-calc{
            background-position: -208px 0;
        }
        .icon-group-calc-disable{
            background-position: -208px -52px;
        }
        .icon-group-print{
            background-position: -260px -52px;
        }
        .icon-group-print:hover{
            background-position: -260px 0;
        }


        .icons-charts{
            float:right;
            background-image: url(img/icons/png/icons-chart.png);
            width: 48px;
            height: 48px;
            cursor: pointer;
        }

        .icon-chart-bar{
            background-position: 0 0;
        }
        .icon-chart-bar-disable{
            background-position: 0 -48px;
        }
        .icon-chart-line{
            background-position: -48px 0;
        }
        .icon-chart-line-disable{
            background-position: -48px -48px;
        }
        .icon-chart-radar{
            background-position: -96px 0;
        }
        .icon-chart-radar-disable{
            background-position: -96px -48px;
        }
        .icon-chart-pie{
            background-position: -144px 0;
        }
        .icon-chart-pie-disable{
            background-position: -144px -48px;
        }

        .icons-arrows{
            float:right;
            background-image: url(img/icons/png/icons-arrow.png);
            width: 48px;
            height: 48px;
            cursor: pointer;
        }

        .icon-green-down{
            background-position: 0 0;
        }
        .icon-green-up{
            background-position: -48px 0;
        }
        .icon-red-down{
            background-position: -96px 0;
        }
        .icon-red-up{
            background-position: -144px 0;
        }

        .icons-list-items{
            float:right;
            background-image: url(img/icons/png/icons-list-itens.png);
            width: 40px;
            height: 44px;
            cursor: pointer;
        }

        .icon-list-item-1{
            background-position: 0 0;
        }

        .icons-list-140-150{
            background-image: url(img/icons/png/icons-list-140x150.png);
            width: 140px;
            height: 150px;
            margin: auto;
        }

        .icon-list-140-150-1{
            background-position: 0 0;
        }

        .label-valor{
            color: #fff;
            size: 48px;
            font-weight: bold;
            text-shadow: 2px 2px 2px #5d5d5d;
        }


    </style>
    {{--@if(substr($base_href, 0,9)=='evbsb1052')--}}
        <style>
            .irs-line-mid, .irs-line-left, .irs-line-right, .irs-bar, irs-bar-edge, .irs-slider {
                background: url(http://evbsb1052.ipea.gov.br/atlasviolencia/img/sprite-skin-flat.png) repeat-x;
            }
        </style>
    {{--@endif--}}

    <div class="container">

        @if(!empty($series))
            <script>serie_id={{$id}}</script>
            <script>serie="{!! $series->titulo !!}";</script>
            <script>tipoValores="{!! $series->tipo_valores !!}";</script>
            <script>from="{!! $from !!}";</script>
            <script>to="{!! $to !!}";</script>
            <script>regions="{!! $regions !!}";</script>
            <script>typeRegion="{!! $typeRegion !!}";</script>
            <script>typeRegionSerie="{!! $typeRegionSerie !!}";</script>
            <div id="pgSerie"></div>
        @else
            <h1 class="text-center">Pesquisa não encontrada!</h1>
        @endif




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