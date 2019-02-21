@extends('layout')
@section('title', 'Mapa')
@section('content')
    <style>
        fieldset{
            border: solid 1px #E4E4E4;
            border-radius: 5px;
        }

        legend{
            font-size: 13px;
            border: 0;
            width: inherit;
            padding: 5px;
            margin: 0 3px;
        }

        .map {
            width: 100%;
            height: 600px;
        }

        .marker{
            height: 60px !important;
            width: 60px !important;
            border:5px solid rgba(255,255,255,0.5);
            font-weight:bold;
            text-align:center;
            border-radius:50%;
            line-height:30px;
            margin-top:-30px !important;
            margin-left:-30px !important;
            padding: 10px;
        }
        .marker2{
            height: 50px !important;
            width: 50px !important;
            border:5px solid rgba(255,255,255,0.5);
            /*font-weight:bold;*/
            text-align:center;
            border-radius:50%;
            line-height:20px;
            margin-top:-25px !important;
            margin-left:-25px !important;
            padding: 10px;
        }
        .markerCor1{
            background: #29b6f6;
        }
        .markerCor2{
            background: #ffd54f;
        }
        .markerCor3{
            background: #ffa726;
        }
        .markerCor4{
            background: #ff5722;
        }
        .markerCor5{
            background: #f44336;
        }

        .fullscreen-icon { background-image: url(lib/leaflet/images/icon-fullscreen.png); }
        /* one selector per rule as explained here : http://www.sitepoint.com/html5-full-screen-api/ */
        .map:-webkit-full-screen { width: 100% !important; height: 100% !important; z-index: 99999; }
        .map:-ms-fullscreen { width: 100% !important; height: 100% !important; z-index: 99999; }
        .map:full-screen { width: 100% !important; height: 100% !important; z-index: 99999; }
        .map:fullscreen { width: 100% !important; height: 100% !important; z-index: 99999; }
        .leaflet-pseudo-fullscreen { position: fixed !important; width: 100% !important; height: 100% !important; top: 0px !important; left: 0px !important; z-index: 99999; }

        .control-container{
            border-radius:5px;
            border: solid 2px rgba(0,0,0,0.2);
            background-color: #fff;
        }

        .control-data-types{
            /*background-color: #fff;*/
            padding: 10px;
            /*border-radius:10px;*/
            cursor: pointer;
            font-weight: bold;
            margin: 0 !important;
            /*border-bottom: solid 1px #e8e8e8;*/
            opacity: 0.3;
        }
        .control-data-types:hover{
            /*background-color: #007bff;*/
            opacity: 1;
        }
        .check-control-data-types{
            /*background-color: #007bff;*/
            /*color: #fff;*/
            opacity: 1;
        }
    </style>

    <style>

        .icon-bar {
            text-align: center;
        }
        .icon-bar img{
            /*color: #FFFFFF;
            font-size: 25px;*/
            width: 60%;
            min-width: 35px;
            /*padding-top: 5px;*/
            float: right;
            margin: 2px 0;
        }
        .txt-bar{
            font-size: 16px;
            font-weight: bold;
            margin-top: 6px;
        }

        .bg-pri{
            background-color: #3498DB;
        }
        .width-bar {
            background-color: #3498DB;
            color: white;
            position: relative;
            margin: 10px 2px 2px 2px;
            font-size: 25px;
        }
    </style>


    <script>
        serie_id = 1;//
        default_regions = "{{$setting->pontos_default_regions}}".split(',');

    </script>
    <div id="page"></div>

@endsection