@extends('.layout')
@section('title', trans('links.news'))
@section('content')
    {{--{{ Counter::count('noticia') }}--}}
    <div class="container">
        <h2>@lang('links.news')</h2>
        <div class="line_title bg-pri"></div>

        @foreach($noticias as $noticia)
            <div class="row">
                <a href="noticia/{{$noticia->id}}/{{clean($noticia->titulo)}}">
                    @if(!empty($noticia->imagem))
                    <div class="col-md-3 col-sm-3">
                        {{--<img src="/imagens/noticias/sm-{{$noticia->imagem}}" alt="{{$noticia->titulo}}" title="{{$noticia->titulo}}" width="100%">--}}
                        <picture>
                            <source srcset="imagens/noticias/sm-{{$noticia->imagem}}" media="(max-width: 468px)">
                            <source srcset="imagens/noticias/md-{{$noticia->imagem}}" media="(max-width: 768px)">
                            <source srcset="imagens/noticias/sm-{{$noticia->imagem}}" class="img-responsive">
                            <img srcset="imagens/noticias/sm-{{$noticia->imagem}}" alt="Imagem sobre {{$noticia->titulo}}," title="Imagem sobre {{$noticia->titulo}}," class="align-img" width="100%">
                        </picture>
                    </div>
                    @endif

                    @if(!empty($noticia->imagem))<div class="col-md-9 col-sm-9">@else<div class="col-md-12 col-sm-12">@endif
                        <h2>{{$noticia->titulo}}</h2>
                        <p>{{str_limit(strip_tags($noticia->descricao), 180)}}</p>
                        <button class="btn btn-none">Continue lendo a notícia</button>
                    </div>
                </a>
            </div>
            <hr>
        @endforeach

        <div>{{ $noticias->links() }}</div>

    </div>
@endsection