<?php
use Illuminate\Support\Facades\DB;
$setting = DB::table('settings')->orderBy('id', 'desc')->first();
$base_href = config('app.url');
?>
        <!DOCTYPE html>
<html class="erro-html">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <title>P&aacute;gina n&atilde;o encontrada de {{$setting->titulo}}</title>

    <link href="css/all.css" rel="stylesheet">
    <link href="css/app.css" rel="stylesheet">
    <link href="lib/font-awesome/css/font-awesome.min.css" rel="stylesheet">

</head>
<body class="erro-body">
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6 col-sm-6 col-xs-12">
            <br>
            <a href="http://{{$base_href}}">
                <picture>
                    <source srcset="imagens/settings/sm-{{$setting->imagem}}" media="(max-width: 468px)">
                    <source srcset="imagens/settings/sm-{{$setting->imagem}}" class="img-responsive">
                    <img srcset="imagens/settings/sm-{{$setting->imagem}}" alt="{{$setting->titulo}}" title="{{$setting->titulo}}">
                </picture>
            </a>
        </div>
        <div class="col-md-6 col-sm-6 hidden-xs text-right ">
            <h1 class="erro-h1">400</h1>
        </div>
    </div>
    <div class="container erro-box">
        <div class="row">
            <div class="col-md-9 col-xs-12">
                <h2 class="erro-h2  hidden-xs">Oops!!!</h2>
                <p class="erro-p">Desculpe, a p&aacute;gina solicitada n&atilde;o foi encontrada.</p>
            </div>
            <div class="col-md-3 col-xs-4 hidden-xs text-right">
                <i class="fa fa-frown-o erro-i" aria-hidden="true"></i>
            </div>
            <div class="col-md-12">
                <br><br><br>
            </div>
            <div class="col-md-6 text-center">
                <a href="" type="button" class="btn btn-lg erro-a">IR PARA HOME</a>
                <br><br>
            </div>
            <div class="col-md-6 text-center">
                <a href="contato" type="button" class="btn btn-lg erro-a">IR PARA O CONTATO</a>
                <br><br>
            </div>
            <div class="col-md-12">
                <br><br>
            </div>
        </div>
    </div>
    <div class="text-center">
        <br><br>
        <p>{{$setting->endereco}} {{$setting->numero}} {{$setting->complemento}} - {{$setting->bairro}} - {{$setting->cidade}} - {{$setting->estado}} - {{$setting->cep}}</p>
        <p>{{$setting->telefone}} @if($setting->telefone2 != "")/@endif {{$setting->telefone2}} @if($setting->telefone3 != "")/@endif {{$setting->telefone3}}</p>
        <p>{{$setting->email}}</p>
    </div>
</div>
</body>
</html>