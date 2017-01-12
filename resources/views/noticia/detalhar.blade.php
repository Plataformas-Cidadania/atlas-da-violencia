@extends('layout')
@section('title', $noticia->titulo)
@section('content')

    <div class="container">
        <h2 class="h1_title" aria-label="{{$noticia->titulo}}, {{strip_tags($noticia->descricao)}}">{{$noticia->titulo}}</h2>
        <div class="line_title bg-pri"></div>
        <div>
            @if(!empty($noticia->imagem))
                <picture>
                    <source srcset="imagens/noticias/sm-{{$noticia->imagem}}" media="(max-width: 468px)">
                    <source srcset="imagens/noticias/md-{{$noticia->imagem}}" media="(max-width: 768px)">
                    <source srcset="imagens/noticias/md-{{$noticia->imagem}}" class="img-responsive">
                    <img srcset="imagens/noticias/md-{{$noticia->imagem}}" alt="{{$noticia->titulo}}" title="{{$noticia->titulo}}" class="align-img" >
                </picture>
            @endif
            <h5 class="text-right">{{Carbon\Carbon::parse($noticia->created_at)->format('d/m/Y - H:i:s')}}</h5>
            @if(!empty($noticia->autor))
                <h5>Autor: {{$noticia->autor}}</h5>
            @endif
            <p>{!!$noticia->descricao!!}</p>
            @if(!empty($noticia->fonte))
                <p>
                    <a href="{{$noticia->link_font}}" target="_blank">
                        <b>{{$noticia->fonte}}</b>
                    </a>
                </p>
            @endif
        </div>
    </div>
@endsection
