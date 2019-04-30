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
            font-size: 12px;
            margin: 0!important;
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
            line-height: 12px!important;
        }
        .hr-bar{
            margin: 10px 0 10px 0!important;
            padding: 0!important;
        }
    </style>

    <?php
        $regioes = \Illuminate\Support\Facades\DB::table('spat.ed_territorios_regioes')->select('edterritorios_codigo')->get();
        $ufs = \Illuminate\Support\Facades\DB::table('spat.ed_territorios_uf')->select('edterritorios_codigo')->get();
        $regioesArray = json_decode(json_encode($regioes), true);
        $ufsArray = json_decode(json_encode($ufs), true);
        $regioesString = implode(",", array_flatten($regioesArray));
        $ufsString = implode(",", array_flatten($ufsArray));
    ?>
    <script>
        serie_id = {{$serie->id}};
        titulo = "{!! $serie->titulo !!}";
        default_regions = "{{$setting->pontos_default_regions}}".split(',');
        regioes = "{{$regioesString}}";
        ufs = "{{$ufsString}}";
    </script>
    <div id="page"></div>



   {{-- <div class="container">

    <div class="card-columns">
        <div class="card">
            <img class="card-img-top" src=".../100px160/" alt="Imagem de capa do card">
            <div class="card-body">
                <h5 class="card-title">Título do card que quebra em uma nova linha</h5>
                <p class="card-text">Este é um card mais longo com suporte a texto embaixo, que funciona como uma introdução a um conteúdo adicional. Este conteúdo é um pouco maior.</p>
            </div>
        </div>
        <div class="card p-3">
            <blockquote class="blockquote mb-0 card-body">
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante.</p>
                <footer class="blockquote-footer">
                    <small class="text-muted">
                        Alguém famoso em <cite title="Título da fonte">Título da fonte222

                        </cite>
                    </small>
                </footer>
            </blockquote>
        </div>
        <div class="card">
            <img class="card-img-top" src=".../100px160/" alt="Imagem de capa do card">
            <div class="card-body">
                <h5 class="card-title">Título do card333333</h5>
                <p class="card-text">Este é um card com suporte a texto embaixo, que funciona como uma introdução a um conteúdo adicional.</p>
                <p class="card-text"><small class="text-muted">Atualizados 3 minutos atrás</small></p>
            </div>
        </div>
        <div class="card bg-primary text-white text-center p-3">
            <blockquote class="blockquote mb-0">
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat.444444</p>
                <footer class="blockquote-footer">
                    <small>
                        Alguém famoso em <cite title="Título da fonte">Título da fonte</cite>
                        <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
                    </small>
                </footer>
            </blockquote>
        </div>
        <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title">Título do card</h5>
                <p class="card-text">Este é um card com suporte a texto embaixo, que funciona como uma introdução a um conteúdo adicional.</p>
                <p class="card-text"><small class="text-muted">Atualizados 3 minutos atrás</small></p>
            </div>
        </div>
        <div class="card">
            <img class="card-img" src=".../100px260/" alt="Imagem do card">
        </div>
        <div class="card p-3 text-right">
            <blockquote class="blockquote mb-0">
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante.111111111111</p>
                <footer class="blockquote-footer">
                    <small class="text-muted">
                        Alguém famoso em <cite title="Título da fonte">Título da fonte</cite>
                    </small>
                </footer>
            </blockquote>
        </div>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Título do card</h5>
                <p class="card-text">Este é um card maior com suporte a texto embaixo, que funciona como uma introdução a um conteúdo adicional. Este card tem o conteúdo ainda maior que o primeiro, para mostrar a altura igual, em ação.</p>
                <p class="card-text"><small class="text-muted">Atualizados 3 minutos atrás</small></p>
            </div>
        </div>
    </div>

    </div>--}}

    <style>
        @media (min-width: 576px){
            .card-columns {
                -webkit-column-count: 3;
                -moz-column-count: 3;
                column-count: 3;
                -webkit-column-gap: 1.25rem;
                -moz-column-gap: 1.25rem;
                column-gap: 1.25rem;
                orphans: 1;
                widows: 1;
            }
        }
        @media (min-width: 576px) {
            .card-columns .card {
                display: inline-block;
                width: 100%;
            }

            .card-columns .card {
                margin-bottom: 0.75rem;
            }

            .card {
                position: relative;
                display: -ms-flexbox;
                display: flex;
                -ms-flex-direction: column;
                flex-direction: column;
                min-width: 0;
                word-wrap: break-word;
                background-color: #fff;
                background-clip: border-box;
                border: 1px solid rgba(0, 0, 0, 0.125);
                border-radius: 0.25rem;
            }
        }
        .card-body {
            -ms-flex: 1 1 auto;
            flex: 1 1 auto;
            padding: 1.25rem;
        }
        .p-3 {
            padding: 1rem !important;
        }
        .card-img-top {
            width: 100%;
            border-top-left-radius: calc(0.25rem - 1px);
            border-top-right-radius: calc(0.25rem - 1px);
        }
    </style>

@endsection