@extends('layout')
@section('title', $quem->titulo)
@section('content')
    {{--{{ Counter::count('quem') }}--}}
    <div class="container">
        <h2 id="calendar" aria-label="{{$quem->titulo}}, {{strip_tags($quem->descricao)}}">{{$quem->titulo}}</h2>
        <div class="line_title bg-pri"></div>
        @if(!empty($quem->imagem))
            <picture>
                <source srcset="/imagens/quemsomos/sm-{{$quem->imagem}}" media="(max-width: 468px)">
                <source srcset="/imagens/quemsomos/md-{{$quem->imagem}}" media="(max-width: 768px)">
                <source srcset="/imagens/quemsomos/md-{{$quem->imagem}}" class="img-responsive">
                <img srcset="/imagens/quemsomos/md-{{$quem->imagem}}" alt="Imagem sobre, {{$quem->titulo}}" title="Imagem sobre, {{$quem->titulo}}" class="align-img">
            </picture>
            {{--<img src="/imagens/quemsomos/md-{{$quem->imagem}}" alt="{{$quem->titulo}}" title="{{$quem->titulo}}" class="align-img">--}}
        @endif
        <p ng-class="{'alto-contraste': altoContrasteAtivo}">{!!$quem->descricao!!}</p>
    </div>
@endsection

