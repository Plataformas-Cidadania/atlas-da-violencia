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



