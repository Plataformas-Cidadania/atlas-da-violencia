@extends('layout')
@section('title', 'Mapa')
@section('content')
    <style>
        .map { width: 100%; height: 600px; }

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
            width: 150px;
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
        .icon-group-info{
            background-position: -312px 0;
        }
        .icon-group-info-disable{
            background-position: -312px -52px;
        }
        .icon-text{
            padding-top: 7px;
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
            /*float:right;*/
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
        .icons-components{
            background-image: url(img/icons-groups-p.png);
            width: 28px;
            height: 28px;
            margin: 1px;
            float: left;
            cursor: pointer;
        }
        .icons-component-print{
            background-position: 0 56px;
        }
        .icons-component-download{
            background-position: -28px 56px;
        }
        .icons-component-csv{
            background-position: -56px 56px;
        }
        .icons-component-pdf{
            background-position: -84px 56px;
        }
        .icons-component-btn{
            background-position: -112px 56px;
        }

        .icons-component-print:hover{
            background-position: 0px 0;
        }
        .icons-component-download:hover{
            background-position: -28px 0;
        }
        .icons-component-csv:hover{
            background-position: -56px 0;
        }
        .icons-component-pdf:hover{
            background-position: -84px 0;
        }
        .icons-component-btn:hover{
            background-position: -112px 0;
        }

        .icons-component-print-active{
            background-position: 0 28px;
        }
        .icons-component-download-active{
            background-position: -28px 28px;
        }
        .icons-component-csv-active{
            background-position: -56px 28px;
        }
        .icons-component-pdf-active{
            background-position: -84px 28px;
        }
        .icons-component-btn-active{
            background-position: -112px 28px;
        }

        .print {
            display:none;
        }

        .print-off {
            display:block;
        }

        @media print{
            .print {
                display:block;
            }
            .print-off {
                display:none;
            }


        }

    </style>


    <div class="container">

        @if(!empty($series))
            <script>
                serie_id={{$id}};
                serie="{!! $series->titulo !!}";
                tipoValores="{!! $series->tipo_valores !!}";
                unidade="{!! $series->unidade !!}";
                from="{!! $from !!}";
                to="{!! $to !!}";
                regions="{!! $regions !!}";
                abrangencia="{{$abrangencia}}";
            </script>
            <?php
                $series->descricao = preg_replace('/\s/',' ',$series->descricao);
            ?>
            <script>
                metadados="{!! $series->descricao !!}";
            </script>

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