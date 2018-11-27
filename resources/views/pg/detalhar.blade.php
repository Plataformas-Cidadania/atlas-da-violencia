@extends('layout')
@section('title', $quem->titulo)
@section('content')
    <div class="container">
        <div class="row"></div>
        <h1 aria-label="{{$quem->titulo}}, {{strip_tags($quem->descricao)}}">{{$quem->titulo}}</h1>
        <div class="line_title bg-pri"></div>
        @if(!empty($quem->imagem))
            <picture>
                <source srcset="/imagens/quemsomos/sm-{{$quem->imagem}}" media="(max-width: 468px)">
                <source srcset="/imagens/quemsomos/md-{{$quem->imagem}}" media="(max-width: 768px)">
                <source srcset="/imagens/quemsomos/md-{{$quem->imagem}}" class="img-responsive">
                <img srcset="/imagens/quemsomos/md-{{$quem->imagem}}" alt="Imagem sobre, {{$quem->titulo}}" title="Imagem sobre, {{$quem->titulo}}" class="align-img">
            </picture>
        @endif
        <p ng-class="{'alto-contraste': altoContrasteAtivo}">{!!$quem->descricao!!}</p>
    </div>
@endsection

