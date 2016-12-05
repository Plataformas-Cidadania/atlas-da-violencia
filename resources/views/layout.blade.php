<?php $rota = Route::getCurrentRoute()->getPath();?>
<?php
    use Illuminate\Support\Facades\DB;
    $setting = DB::table('settings')->orderBy('id', 'desc')->first();
?>
<!doctype html>
<html lang="pt-bt">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0" />
        <title>{{$setting->titulo}} - @yield('title')</title>
        @include('conexoes.css')

    </head>
    <body ng-app="ipeaApp"  ng-controller="appCtrl" ng-class="{'alto-contraste': altoContrasteAtivo}">
        @include('layouts.layout1')
        @include('conexoes.js')
    </body>
</html>


{{--<script defer="defer" src="//barra.brasil.gov.br/barra.js" type="text/javascript"></script>--}}

