@extends('layout')
@section('title', 'Bem Vindo')
@section('content')

    <br><br>

    <article>
        @if(!empty($links) && $setting->links==1)
            <div class="container block" data-move-x="500px">
                <div class="row">
                    <br><br>
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
                                @if($link->tipo==0)
                                    <a href="filtros-series/{{$link->link}}/{{clean($link->titulo)}}">
                                @elseif($link->tipo==1)
                                    <a href="redirecionamento/{{$link->id}}/{{clean($link->titulo)}}">
                                @else
                                    <a href="em-construcao">
                                @endif

                                     <img class="imgLinks" srcset="imagens/links/{{$link->imagem}}" onerror="setSrc(this);" alt="Imagem sobre {{$link->titulo}}" title="Imagem sobre {{$link->titulo}}" >

                                    <script type="text/javascript">
                                        function setSrc(e){
                                            e.setAttribute('srcset', 'img/fallback.png');
                                            e.setAttribute('onerror', '');
                                        }
                                    </script>

                                    <div>
                                        <h2 class="titulo-itens" ng-class="{'alto-contraste': altoContrasteAtivo}" href="{{$link->link}}">{{$link->titulo}}</h2>
                                    </div>
                                    <div class="box-itens-filete"></div>
                                </a>
                            </div>
                        </div>
                        <?php $cont_animecao ++;?>
                    @endforeach
                </div>
            </div>
        @endif
    </article>

    <div class="container">
        @foreach($presentationRows as $row)
            <div class="row">
                @foreach($row as $element)
                    @if($element->position == "full")
                        <div class="col-md-12 block" data-move-y="300px">
                            @if($element->type==1)
                                <div style="margin: 0 20%; font-weight: bold;" class="text-center">
                                    <br>
                                    {!! $element->content !!}
                                </div>
                                <hr>
                                <div class="line_title bg-pri" style="width: 100px; margin: -20px auto"></div>

                            @endif
                            @if($element->type==2)
                                @if($element->chart_type==1)
                                    @include("presentation-charts/chartbar")
                                @endif
                                @if($element->chart_type==2)
                                    @include("presentation-charts/chartline")
                                @endif
                                @if($element->chart_type==3)
                                    @include("presentation-charts/chartstacked")
                                @endif
                                @if($element->chart_type==4)
                                    @include("presentation-charts/chartdashed")
                                @endif
                                @if($element->chart_type==5)
                                    @include("presentation-charts/chartarea")
                                @endif
                                @if($element->chart_type==6)
                                    @include("presentation-charts/chartpercent")
                                @endif
                                @if($element->chart_type==7)
                                    @include("presentation-charts/chartnegative")
                                @endif
                            @endif
                            @if($element->type==3)
                                <img src="imagens/presentation-elements/{{$element->content}}" width="100%" alt="{{$element->content}}">
                            @endif
                            @if($element->type==4)
                                <iframe src="arquivos/presentation-elements/{{$element->content}}"
                                        width="100%"
                                        frameborder="0"
                                        @if(!empty($element->height)) style="height: {{$element->height}}px" @else onload="resizeIframe(this) @endif"
                                ></iframe>
                            @endif
                        </div>
                    @else
                        <div class="col-md-6 block" @if($element->position=='left') data-move-x="-300px" @endif @if($element->position=='right') data-move-x="300px" @endif>
                            @if($element->type==1)
                                {!! $element->content !!}
                            @endif
                            @if($element->type==2)
                                @if($element->chart_type==1)
                                    @include("presentation-charts/chartbar")
                                @endif
                                    @if($element->chart_type==2)
                                        @include("presentation-charts/chartline")
                                    @endif
                                    @if($element->chart_type==3)
                                        @include("presentation-charts/chartstacked")
                                    @endif
                                    @if($element->chart_type==4)
                                        @include("presentation-charts/chartdashed")
                                    @endif
                                    @if($element->chart_type==5)
                                        @include("presentation-charts/chartarea")
                                    @endif
                                    @if($element->chart_type==6)
                                        @include("presentation-charts/chartpercent")
                                    @endif
                                    @if($element->chart_type==7)
                                        @include("presentation-charts/chartnegative")
                                    @endif
                            @endif
                            @if($element->type==3)
                                <img src="imagens/presentation-elements/{{$element->content}}" width="100%" alt="{{$element->content}}">
                            @endif
                            @if($element->type==4)
                                    <iframe src="arquivos/presentation-elements/{{$element->content}}"
                                            width="100%"
                                            frameborder="0"
                                            @if(!empty($element->height)) style="height: {{$element->height}}px" @else onload="resizeIframe(this) @endif"
                                    ></iframe>
                            @endif
                        </div>
                    @endif
                @endforeach
            </div>
            <br><br><br>
        @endforeach
    </div>

    <article>

        <br><br>


        @if(!empty($downloads))
        <div class="container">
            <div class="row text-center">
                @foreach($tituloLinhaTempo as $titulo)
                <h2 class="box-titulo">
                    {{$titulo->titulo}}
                </h2>
                    {!! $titulo->descricao !!}
                @endforeach
                <br><br>

                <div class="line_title bg-qui line-temp-barr"></div>
                @foreach($downloads as $download)
                <div class="col-md-4 text-center">
                    <img src="img/marker.png" alt=""><br><br><br><br>
                    <div class="row">
                        <div class="col-md-12">
                            <p class="line-temp-font">{{$download->titulo}}</p><br><br><br>
                        </div>
                        <div class="col-md-6">
                            <a href="download/{{$download->id}}/{{clean($download->titulo)}}">
                                <button class="btn btn-default">@lang('buttons.access-file')</button>
                            </a>
                        </div>
                        <div class="col-md-6">
                            <a href="download-zip/{{$download->id}}">
                                <button class="btn btn-default">@lang('buttons.download-file')</button>
                            </a>
                        </div>
                    </div>
                    {{--<a href="download/{{$download->id}}/{{clean($download->titulo)}}">
                        <p class="line-temp-font">{{$download->titulo}}</p><br><br><br>
                        <button class="btn btn-default">@lang('buttons.download-pdf')</button>
                    </a>--}}
                </div>
                @endforeach

            </div>
        </div>

        <br><br><br><br>
        @endif

        {{--STRAT INDICE--}}
        @if(count($indices) > 0)
        <br><br>
        <div class="bg-pri" ng-class="{'alto-contraste': altoContrasteAtivo}">
            <div class="container">
                <div class="row box-hoje">
                    <h3>@lang('pages.indexes')</h3>
                    <?php $cont_indice = 1;?>
                    @foreach($indices as $indice)
                    <div class="col-md-3">
                        <h2 id="contadorIndice<?php echo $cont_indice;?>"></h2>
                        <p id="nomeIndice<?php echo $cont_indice;?>"></p>
                    </div>
                    <?php $cont_indice ++;?>
                    @endforeach
                </div>
                <br>
            </div>
        </div>
        <br><br>
        @endif
        {{--END INDICE--}}

        {{--STRAT NOTICIAS--}}
        @if(!empty($noticias))
        <div class="container">
            <div class="row">
                <h2 class="box-titulo">@lang('links.news')</h2>
                @foreach($noticias as $noticia)
                    <div class="col-md-4">
                        <a href="noticia/{{$noticia->id}}/{{clean($noticia->titulo)}}" aria-label="{{$noticia->titulo}}, {{str_limit(strip_tags($noticia->descricao), 180)}}, continue lendo a matéria">
                            <h3 class="h3-m">{{$noticia->titulo}}</h3>
                            <p>{{str_limit(strip_tags($noticia->descricao), 180)}}</p>
                        </a>
                    </div>
                @endforeach
                <div class="row text-center">
                    <div class="col-md-12 space-top">
                        <a href="noticias/veja-todas-as-noticias" role="button">
                            <button class="btn btn-sec btn-padding btn-base" style="font-size: 14px;">@lang('buttons.view-more') @lang('links.news')</button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endif
        {{--END NOTICIAS--}}

        {{--STRAT BEM VINDO--}}
        @if(!empty($bemvindo))
        <br><br>
        <div class="bg-qui" ng-class="{'alto-contraste': altoContrasteAtivo}">
            <div class="container">
                <br><br>
                <p>{{strip_tags($bemvindo->descricao)}}</p>
                <br><br>
            </div>
        </div>
        @endif
        {{--END BEM VINDO--}}

        {{--STRAT PARCEIROS--}}
        @if(!empty($parceiros))
            <br><br>
        <div class="container">
            <div class="row">
                @foreach($parceiros as $parceiro)
                    <div class="col-md-3">
                        <a href="{{$parceiro->url}}" aria-label="{{$parceiro->titulo}}">
                            <picture>
                                <source srcset="imagens/parceiros/sm-{{$parceiro->imagem}}" media="(max-width: 468px)">
                                <source srcset="imagens/parceiros/sm-{{$parceiro->imagem}}" media="(max-width: 768px)">
                                <source srcset="imagens/parceiros/sm-{{$parceiro->imagem}}" class="img-responsive">
                                <img srcset="imagens/parceiros/sm-{{$parceiro->imagem}}" alt="Imagem sobre {{$parceiro->titulo}}" title="Imagem sobre {{$parceiro->titulo}}" width="100%">
                            </picture>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
        @endif
        {{--END PARCEIROS--}}
        <br><br>
        <br><br>
        <div id="newsletter"></div>
        <br><br>

    </article>

@endsection
