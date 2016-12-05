@extends('layout')
@section('title', 'Bem Vindo')
@section('content')

    <div class="container">
        <div class="row">

            <div class="col-md-12 col-sm-12">
                <div class="row">
                    <?php $cont = 0;?>
                @foreach($links as $link)
                <div class="col-md-3 col-sm-12 box-itens " ng-class="{'alto-contraste': altoContrasteAtivo}" >
                    <div>
                        <a href="{{$link->link}}" target="_blank">
                            <picture>
                                <source srcset="/imagens/links/{{$link->imagem}}" media="(max-width: 468px)">
                                <source srcset="/imagens/links/{{$link->imagem}}" media="(max-width: 768px)">
                                <source srcset="/imagens/links/{{$link->imagem}}" class="img-responsive">
                                <img srcset="/imagens/links/{{$link->imagem}}" alt="Imagem sobre {{$link->titulo}}" title="Imagem sobre {{$link->titulo}}">
                            </picture>
                            <div class="bg-sex">
                                <h2 class="titulo-itens" ng-class="{'alto-contraste': altoContrasteAtivo}" href="{{$link->link}}">{{$link->titulo}}</h2>
                                {{--<p ng-class="{'alto-contraste': altoContrasteAtivo}" href="{{$link->link}}">{{$link->descricao}}</p>--}}
                            </div>
                           <div class="box-itens-filete"></div>
                        </a>
                    </div>
                </div>
                <?php $cont ++;?>
                @endforeach
            </div>

                <div class="row box_txt text-left bg-qui">
                    <div class="col-md-12">
                        <a href="/quem/conheca-o-ipea" >
                            <p>{{strip_tags($bemvindo->descricao)}}</p>
                        </a>
                    </div>
                </div>

                <br><br><br>

                <div class="row">
                    <h2 class="box-titulo">Notícias</h2>
                    @foreach($noticias as $noticia)
                        <div class="col-md-6">
                            <a href="noticia/{{$noticia->id}}/{{clean($noticia->titulo)}}" aria-label="{{$noticia->titulo}}, {{str_limit(strip_tags($noticia->descricao), 180)}}, continue lendo a matéria">
                                <h3 class="h3-m">{{$noticia->titulo}}</h3>
                                <p>{{str_limit(strip_tags($noticia->descricao), 180)}}</p>
                            </a>
                        </div>
                    @endforeach
                    <div class="row text-center">
                        <div class="col-md-12 space-top">
                            <a href="/noticias/veja-todas-as-noticias" role="button">
                                <button class="btn btn-sec btn-padding btn-base">VER MAIS NOTÍCIAS</button>
                            </a>
                        </div>
                    </div>
                </div>



            </div>


        </div>
    </div>

    <div class="container-fluid bg-pri">
        asd
    </div>

    <article>




    </article>

@endsection


