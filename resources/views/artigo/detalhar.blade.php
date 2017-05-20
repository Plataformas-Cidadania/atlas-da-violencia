@extends('layout')
@section('title', $artigo->titulo)
@section('content')

    <div class="container">
        <h2 class="h1_title" aria-label="{{$artigo->titulo}}, {{strip_tags($artigo->descricao)}}">{{$artigo->titulo}}</h2>
        <div class="line_title bg-pri"></div>
        <div class="row">
            <div class="col-md-9">
                @if(!empty($artigo->imagem))
                    <picture>
                        <source srcset="imagens/artigos/sm-{{$artigo->imagem}}" media="(max-width: 468px)">
                        <source srcset="imagens/artigos/md-{{$artigo->imagem}}" media="(max-width: 768px)">
                        <source srcset="imagens/artigos/md-{{$artigo->imagem}}" class="img-responsive">
                        <img srcset="imagens/artigos/md-{{$artigo->imagem}}" alt="{{$artigo->titulo}}" title="{{$artigo->titulo}}" class="align-img" >
                    </picture>
                    <h6>{!! $artigo->legenda !!}</h6>
                @endif
                <p>{!!$artigo->descricao!!}</p>
                @if(!empty($artigo->fonte))
                    <p>
                        <a href="{{$artigo->link_font}}" target="_blank">
                            <b>{{$artigo->fonte}}</b>
                        </a>
                    </p>
                @endif
                <div class="row">
                    <div class="col-md-12">
                        @if(!empty($artigo->link))
                            <iframe src="{{$artigo->link}}" height="1000" width="100%"  frameborder="0"></iframe>
                        @else
                            <iframe src="arquivos/artigos/{{$artigo->arquivo}}" height="1000" width="100%"  frameborder="0"></iframe>
                        @endif

                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <br>
                <h3><i class="fa fa-user" aria-hidden="true"></i> @lang('pages.author')</h3>
                <hr>
                <h5>
                    @foreach($autores as $autor)
                        {{$autor->titulo}}<br><br>
                    @endforeach
                </h5>
                <hr>
                <p><i class="fa fa-clock-o" aria-hidden="true"></i> {{Carbon\Carbon::parse($artigo->created_at)->format('d/m/Y - H:i:s')}}</p>
                <br>
                @if(!empty($artigo->link))
                    <a href="{{$artigo->link}}" target="_blank" class="btn btn-danger text-right" style="width: 100%;">
                        @else
                    <a href="arquivos/artigos/{{$artigo->arquivo}}" target="_blank" class="btn btn-danger text-right" style="width: 100%;">
                        @endif
                        <br><i class="fa fa-file-pdf-o fa-2x" aria-hidden="true"></i> <br> Acessar arquivo<br><br>
                    </a>
            </div>




        </div>
    </div>
@endsection
