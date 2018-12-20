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

    //echo $base_href;

/*$base_href = $_SERVER['HTTP_HOST'];
if(substr($base_href, 0,9)=='evbsb1052'){
    $base_href .= '/atlasviolencia/';
}*/
?>
<!doctype html>
<html lang="pt-bt">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0" />
        <title>@if(count($setting) > 0) {{$setting->titulo}} - @yield('title') @endif</title>
        <base href="http://{{$base_href}}@if($base_href=='10.0.52.46')/@endif">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        @foreach(config('constants.FAVICONS_SIZES') as $size)
            @if(count($favicons) > 0)
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

    </head>
    <body ng-app="ipeaApp"  ng-controller="appCtrl" ng-class="{'alto-contraste': altoContrasteAtivo}">
        @include('layouts.layout1')
        @include('conexoes.js')
    </body>
</html>


<script defer="defer" src="//barra.brasil.gov.br/barra.js" type="text/javascript"></script>



