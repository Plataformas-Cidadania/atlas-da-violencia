@extends('layout')
@section('title', 'Bem Vindo')
@section('content')



    <style>
        .filtros {
            width: 20%;
            float: left;
            height: 200px;
        }
        @media (max-width: 768px) {
            .filtros {
                width: 100%;
            }
        }
    </style>

    <article>
        <br><br>

        <div class="container block" data-move-x="500px">
            <div class="row">
                <?php $cont_animecao = 0;?>
                @foreach($links as $link)
                        <?php
                        switch ($cont_animecao%2) {
                            case 0:
                                $valor_anime = "-300px";
                                break;
                            default:
                                $valor_anime = "300px";
                        }?>
                    <div class="filtros box-itens block" data-move-x="<?php echo $valor_anime;?>" ng-class="{'alto-contraste': altoContrasteAtivo}" >
                        <div>
                            <a href="{{$link->link}}">
                                <picture>
                                    <source srcset="imagens/links/{{$link->imagem}}" media="(max-width: 468px)">
                                    <source srcset="imagens/links/{{$link->imagem}}" media="(max-width: 768px)">
                                    <source srcset="imagens/links/{{$link->imagem}}" class="img-responsive">
                                    <img srcset="imagens/links/{{$link->imagem}}" alt="Imagem sobre {{$link->titulo}}" title="Imagem sobre {{$link->titulo}}">
                                </picture>
                                <div class="bg-sex">
                                    <h2 class="titulo-itens" ng-class="{'alto-contraste': altoContrasteAtivo}" href="{{$link->link}}">{{$link->titulo}}</h2>
                                    {{--<p ng-class="{'alto-contraste': altoContrasteAtivo}" href="{{$link->link}}">{{$link->descricao}}</p>--}}
                                </div>
                                <div class="box-itens-filete"></div>
                            </a>
                        </div>
                    </div>
                    <?php $cont_animecao ++;?>
                @endforeach
            </div>
        </div>

        <br><br>
        <div class="bg-pri">
            <div class="container">
                <div class="row box-hoje">
                    <h3>Índices de hoje</h3>
                    <div class="col-md-3">
                        <h2 id="contadorIndice1"></h2>
                        <p id="nomeIndice1"></p>
                    </div>
                    <div class="col-md-3">
                        <h2 id="contadorIndice2">00000</h2>
                        <p id="nomeIndice2"></p>
                    </div>
                    <div class="col-md-3">
                        <h2 id="contadorIndice3">00000</h2>
                        <p id="nomeIndice3"></p>
                    </div>
                    <div class="col-md-3">
                        <h2 id="contadorIndice4">00000</h2>
                        <p id="nomeIndice4"></p>
                    </div>
                </div>
            </div>
        </div>
        <br><br>

        <div class="container">
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
                        <a href="noticias/veja-todas-as-noticias" role="button">
                            <button class="btn btn-sec btn-padding btn-base">VER MAIS NOTÍCIAS</button>
                        </a>
                    </div>
                </div>
            </div>
        </div>


        <br><br>
        <div class="bg-qui">
            <div class="container">
                <a href="quem/conheca-o-ipea" >
                    <br><br>
                    <p>{{strip_tags($bemvindo->descricao)}}</p>
                    <br><br>
                </a>
            </div>
        </div>
        <br><br>










    </article>

@endsection


