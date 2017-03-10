@extends('layout')
@section('title', $artigo->titulo)
@section('content')

    <div class="container">
        <h2 class="h1_title" aria-label="{{$artigo->titulo}}, {{strip_tags($artigo->descricao)}}">{{$artigo->titulo}}</h2>
        <div class="line_title bg-pri"></div>
        <div>
            @if(!empty($artigo->imagem))
                <picture>
                    <source srcset="imagens/artigos/sm-{{$artigo->imagem}}" media="(max-width: 468px)">
                    <source srcset="imagens/artigos/md-{{$artigo->imagem}}" media="(max-width: 768px)">
                    <source srcset="imagens/artigos/md-{{$artigo->imagem}}" class="img-responsive">
                    <img srcset="imagens/artigos/md-{{$artigo->imagem}}" alt="{{$artigo->titulo}}" title="{{$artigo->titulo}}" class="align-img" >
                </picture>
                <h6>{!! $artigo->legenda !!}</h6>
            @endif
            <h5 class="text-right">{{Carbon\Carbon::parse($artigo->created_at)->format('d/m/Y - H:i:s')}}</h5>
            @if(!empty($artigo->autor))
                <h5>Autor: {{$artigo->autor}}</h5>
            @endif
            <p>{!!$artigo->descricao!!}</p>
            @if(!empty($artigo->fonte))
                <p>
                    <a href="{{$artigo->link_font}}" target="_blank">
                        <b>{{$artigo->fonte}}</b>
                    </a>
                </p>
            @endif
        </div>
    </div>
@endsection
