@extends('layout')
@section('title', $webdoor->titulo)
@section('content')
    {{--{{ Counter::count('webdoor') }}--}}
    <div class="container">
        <h2 id="calendar" aria-label="{{$webdoor->titulo}}, {{strip_tags($webdoor->descricao)}}">{{$webdoor->titulo}}</h2>
        <div class="line_title bg-pri"></div>
        @if(!empty($webdoor->imagem))
            <picture>
                <source srcset="/imagens/webdoors/sm-{{$webdoor->imagem}}" media="(max-width: 468px)">
                <source srcset="/imagens/webdoors/md-{{$webdoor->imagem}}" media="(max-width: 768px)">
                <source srcset="/imagens/webdoors/lg-{{$webdoor->imagem}}" class="img-responsive">
                <img srcset="/imagens/webdoors/lg-{{$webdoor->imagem}}" alt="Imagem sobre, {{$webdoor->titulo}}" title="Imagem sobre, {{$webdoor->titulo}}">
            </picture>
            {{--<img src="/imagens/webdoors/md-{{$webdoor->imagem}}" alt="{{$webdoor->titulo}}" title="{{$webdoor->titulo}}" class="align-img">--}}
        @endif
        <p ng-class="{'alto-contraste': altoContrasteAtivo}">{!!$webdoor->descricao!!}</p>
    </div>
@endsection

