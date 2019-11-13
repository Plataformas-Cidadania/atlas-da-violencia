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
        <script src="lib/apexcharts/prop-types.min.js"></script>
        <script src="lib/apexcharts/apexcharts.min.js"></script>

        <script>
            function resizeIframe(obj) {
                obj.style.height = obj.contentWindow.document.body.scrollHeight + 50 + 'px';
            }
        </script>

        @if($setting->analytics_tipo==1 && $setting->analytics_id!="")
        <!-- Piwik -->
        <script type="text/javascript">
            var _paq = _paq || [];
            _paq.push(['trackPageView']);
            _paq.push(['enableLinkTracking']);
            (function() {
                var u="//webstats.ipea.gov.br/piwik/";
                _paq.push(['setTrackerUrl', u+'piwik.php']);
                _paq.push(['setSiteId', {{$setting->analytics_id}}]);
                var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
                g.type='text/javascript'; g.async=true; g.defer=true; g.src=u+'piwik.js'; s.parentNode.insertBefore(g,s);
            })();
        </script>
        <noscript><p><img src="{{$setting->analytics_url}}?idsite={{$setting->analytics_id}}" style="border:0;" alt="" /></p></noscript>
        <!-- End Piwik Code -->
        @endif


    </head>
    <body ng-app="ipeaApp"  ng-controller="appCtrl" ng-class="{'alto-contraste': altoContrasteAtivo}">
        @include('layouts.layout1')
        @include('conexoes.js')
    </body>
</html>
<script defer="defer" src="//barra.brasil.gov.br/barra.js" type="text/javascript"></script>



