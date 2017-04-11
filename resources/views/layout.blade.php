<?php $rota = Route::getCurrentRoute()->getPath();?>
<?php
    use Illuminate\Support\Facades\DB;
    $setting = DB::table('settings')->orderBy('id', 'desc')->first();
    $idiomas = DB::table('idiomas')->orderBy('id')->get();

    $base_href = $_SERVER['HTTP_HOST'];
    if(substr($base_href, 0,9)=='evbsb1052'){
        $base_href .= '/atlasviolencia/';
    }
?>
<!doctype html>
<html lang="pt-bt">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0" />
        <title>{{$setting->titulo}} - @yield('title')</title>
        <base href="http://{{$base_href}}">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        @include('conexoes.css')

    </head>
    <body ng-app="ipeaApp"  ng-controller="appCtrl" ng-class="{'alto-contraste': altoContrasteAtivo}">
        @include('layouts.layout1')
        @include('conexoes.js')
    </body>
</html>


{{--<script defer="defer" src="//barra.brasil.gov.br/barra.js" type="text/javascript"></script>--}}

