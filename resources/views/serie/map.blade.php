<?php $rota = Route::getCurrentRoute()->getPath();?>
<?php
use Illuminate\Support\Facades\DB;
$lang =  App::getLocale();

$setting = DB::table('settings')->orderBy('id', 'desc')->first();
$links = DB::table('links')->where('idioma_sigla', $lang)->orderBy('posicao')->take(10)->get();
$idiomas = DB::table('idiomas')->where('status', 1)->orderBy('id')->get();
$apoios = DB::table('apoios')->orderBy('posicao')->get();
$favicons = DB::table('favicons')->first();
$indicadores = DB::table('webindicadores')->get();

$base_href = config('app.url');


$barra = "";
$ips = ["10.0.52.46", "181.191.91.55", "10.31.47.3"];
foreach($ips as $ip){
    if($base_href==$ip){
        $barra = "/";
    }
}
?>
        <!doctype html>
<html lang="pt-bt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0" />
    <title>@if(!empty($setting)) {{$setting->titulo}} - @yield('title') @endif</title>
    <base href="http://{{$base_href}}{{$barra}}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @foreach(config('constants.FAVICONS_SIZES') as $size)
        @if(!empty($favicons))
            <link rel="icon" href="imagens/favicons/{{$size}}-{{$favicons->imagem}}" sizes="{{$size}}">
        @endif
    @endforeach

    @include('conexoes.css')

    <style>
        /*Bootstrap.less*/
        .nav > li a:hover{
            background-color: {{$setting->cor1}} !important;
        }
        .nav > li a.corrente{
            background-color:{{$setting->cor1}} !important;
        }
        .corrente{
            background-color: {{$setting->cor1}} !important;
        }
        /*theme.less*/
        .list{
            background-color: {{$setting->cor3}} !important;
        }
        .box-itens:hover .btn-rectangle{
            background-color: {{$setting->cor1}} !important;
        }
        .h3-m{
            color: {{$setting->cor1}} !important;
        }
        /*style.less*/
        .bg-pri{
            background-color: {{$setting->cor1}} !important;
        }
        .bg-sec{
            background-color: {{$setting->cor2}} !important;
        }
        .bg-ter{
            background-color: {{$setting->cor3}} !important;
        }
        .bg-qua{
            background-color: {{$setting->cor4}} !important;
        }
        .bg-qui{
            background-color: {{$setting->cor5}};
        }
        .ft-pri{
            color: {{$setting->cor1}} !important;
        }
        .ft-sec{
            color: {{$setting->cor2}} !important;
        }
        .ft-ter{
            color: {{$setting->cor3}} !important;
        }
        .ft-qua{
            color: {{$setting->cor4}} !important;
        }
        .ft-qui{
            color: {{$setting->cor5}} !important;
        }

        /*Menus.less*/
        .menu-local ul li a:hover {
            background-color: {{$setting->cor5}} !important;
        }
        .menu-global-box{
            border-top: solid 5px {{$setting->cor1}} !important;
        }
        .menu-global-box li a:hover{
            background-color: {{$setting->cor1}} !important;
        }
        .menu-vertical li a:hover{
            background-color: {{$setting->cor1}} !important;
        }
        /*btns.less*/
        .btn-circle{
            background-color: {{$setting->cor3}} !important;
        }
        .btn-circle:hover {
            background-color: {{$setting->cor1}} !important;
        }

        .btn-rectangle{
            background-color: {{$setting->cor3}} !important;
        }
        .btn-rectangle:hover {
            background-color: {{$setting->cor1}} !important;
        }
        .btn-sec {
            background-color: {{$setting->cor1}} !important;
        }
        .btn-sec:hover {
            background-color: {{$setting->cor1}} !important;
        }
        #footer-brasil {
            background: none repeat scroll 0% 0% {{$setting->cor1}} !important;;
        }
        .filtros {
            width: {{$setting->qtd_temas_home}}%;
        }

        @media (max-width: 800px) {
            .filtros {
                width: 33.33333333%;
            }
        }
        @media (max-width: 480px) {
            .filtros {
                width: 100%;
            }
        }
    </style>

    <style>

        foreignObject{
            overflow-x: auto !important;
        }

        .apexcharts-legend{
            top: 20px !important;
        }


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
            min-width: 150px;
        }
        .info h4 {
            margin: 0 0 5px;
            color: #777;
        }


    </style>


</head>
<body>
<script>
    serie_id = "{{$id}}";
    serie = "{!! $serie !!}";
    tipoUnidade = "{{$tipoUnidade}}";
    periodo = "{{$periodo}}";
    regions = "{{$regions}}";
    abrangencia = "{{$abrangencia}}";

    lang_mouse_over_region = "@lang('react.mouse-over-region')";
</script>
<div id="map"></div>
@include('conexoes.js')

</body>
</html>



