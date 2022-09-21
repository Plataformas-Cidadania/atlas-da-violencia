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
                        }

                        if(is_numeric($link->link)){
                            $tema_id = $link->link;

                            $lang =  \App::getLocale();

                            $tema = \App\Tema::select('idiomas_temas.resumida')
                                ->where('idiomas_temas.idioma_sigla', $lang)
                                ->where('temas.id', $tema_id)
                                ->join('idiomas_temas', 'idiomas_temas.tema_id', '=', 'temas.id')
                                ->first();
                        }


                        ?>
                        <div class="filtros box-itens block" data-move-x="<?php echo $valor_anime;?>" ng-class="{'alto-contraste': altoContrasteAtivo}" >
                            <div>
                                @if($link->tipo==0)
                                    <a href="filtros-series/{{$link->link}}/{{clean($link->titulo)}}">
                                @elseif($link->tipo==1)
                                    <a href="redirecionamento/{{$link->id}}/{{clean($link->titulo)}}">
                                @else
                                    <a href="em-construcao">
                                @endif

                                     <img class="imgLinks" srcset="imagens/links/{{$link->imagem}}"
                                          onerror="setSrc(this);"
                                          @if(is_numeric($link->link) && !empty($tema))
                                          alt="{{$tema->resumida}}"
                                          title="{{$tema->resumida}}"
                                          @else
                                          alt="Imagem sobre {{$link->titulo}}"
                                          title="Imagem sobre {{$link->titulo}}"
                                          @endif
                                     >

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

    {{--VIDEOS E DESTAQUES DE TEXTO--}}
    @if(true)
    <div class="container">
            <?php
            $col_video = 6;
            $col_carousel = 6;
            $height_artigo = '130px';
            ?>
        <div class="row">

                <div class="col-xs-12 col-sm-{{$col_video}} col-md-{{$col_video}} col-lg-{{$col_video}}" ng-init="showVideo=false">
                    <iframe width="100%" height="315"
                            src="https://www.youtube.com/embed/@if(!empty($video)){{codigoYoutube($video->link_video)}}@endif"
                            frameborder="0" allowfullscreen></iframe>
                </div>


                <div class="col-xs-12 col-sm-{{$col_carousel}} col-md-{{$col_carousel}} col-lg-{{$col_carousel}} "
                     ng-class="{'alto-contraste': altoContrasteAtivo}">
                    <div class="box-destaque">
                        <div id="carousel2" class="carousel slide" data-ride="carousel">
                            <!-- Indicators -->
                            <ol class="carousel-indicators">
                                    <?php $cont_itens_wd2 = 0;?>
                                @foreach($destaques as $destaque)
                                    <li data-target="#carousel2" data-slide-to="<?php echo $cont_itens_wd2;?>"
                                        @if($cont_itens_wd2==0) class="active" @endif></li>
                                        <?php $cont_itens_wd2++;?>
                                @endforeach


                            </ol>
                            <!-- Wrapper for slides -->
                            <div class="carousel-inner">
                                {{--DESTAQUE--}}
                                    <?php $cont = 0;?>
                                @foreach($destaques as $destaque)
                                    <a href="{{$destaque->link}}" class="item @if($cont==0) active @endif">
                                        <div>
                                            <h1 ng-class="{'alto-contraste': altoContrasteAtivo}">{{$destaque->titulo}}</h1>
                                            <h3 ng-class="{'alto-contraste': altoContrasteAtivo}">{{$destaque->chamada}}</h3>
                                        </div>
                                    </a>
                                        <?php $cont++;?>
                                @endforeach
                                {{----}}
                            </div>
                            <!-- Controls -->
                            <a class="left carousel-control hidden-xs" href="#carousel2" role="button"
                               data-slide="prev">
                                <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                                <span class="sr-only">Previous</span>
                            </a>
                            <a class="right carousel-control hidden-xs" href="#carousel2" role="button"
                               data-slide="next">
                                <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                                <span class="sr-only">Next</span>
                            </a>
                        </div>
                    </div>
                </div>

        </div>

        <div class="marking bg-qua"></div>
    </div>

    <style>
        .carousel-indicators {
            bottom: -12px!important;;
        }
        .carousel-caption{
            background-color:rgba(0, 0, 0, 0.5);
            width: 100%;
            right: 0%;
            left: 0%;
            padding-bottom: 0;
            bottom: 0;
        }
        .carousel-caption h3{
            color: #FFFFFF!important;
            text-shadow: none;
            padding:0 0 0 15px;
            margin: 0;
            text-align: left;
            font-size: 18px!important;
        }
        .carousel-caption p{
            color: #FFFFFF!important;
            text-shadow: none;
            padding:0 0 15px 15px;
            margin: 0;
            text-align: left;
            font-size: 15px!important;
        }
        canvas {
            -moz-user-select: none;
            -webkit-user-select: none;
            -ms-user-select: none;
        }
        .canvas-titulo{
            font-size: 16px;
            margin: 30px 0 -30px 0;
            padding: 0;
            position: relative;
            text-align: center;
        }
    </style>
    @endif
    {{--FIM VIDEOS E DESTAQUES DE TEXTO--}}

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



        @foreach($presentationRows as $key =>  $row)
            <ul class="nav nav-tabs nav-chart" role="tablist">
                @foreach($row as $key_aba => $element)
                    @if($element->position == "full")
                        @if($element->chart_type==99)
                            <li role="presentation" @if($key_aba==0) class="active" @endif><a href="#aba{{$key_aba}}" aria-controls="aba{{$key_aba}}" role="tab" data-toggle="tab">{{$element->title}}</a></li>
                        @endif
                    @endif
                @endforeach
            </ul>
            <div class="tab-content">
                @foreach($row as $key_aba_cont => $element)
                    @if($element->position == "full")
                        @if($element->chart_type==99)
                            {{--{{$element->title}}--}}
                            <div role="tabpanel" class="tab-pane  @if($key_aba_cont==0) active @endif" id="aba{{$key_aba_cont}}">@include("presentation-charts/chartline")</div>
                        @endif
                    @endif
                @endforeach
            </div>
        @endforeach


    </div>

    <article>

        {{--<br><br>--}}

        <?php /* ?>
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
        <?php */ ?>

        {{--START INDICE--}}
        <?php  ?>
        @if(count($indices) > 0)
        <br><br>
        <div class="bg-pri" ng-class="{'alto-contraste': altoContrasteAtivo}">
            <div class="container">
                <div class="row box-hoje">
                    <h2 style="color: #EF921A;"><strong>@lang('pages.indexes')</strong></h2>
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
        <?php  ?>
        {{--END INDICE--}}

        <?php /*
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
        */ ?>

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
        {{--@if(!empty($parceiros))
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
        <br><br>
        @endif--}}
        {{--END PARCEIROS--}}
        <br><br>
        <div id="newsletter"></div>
        <br><br>

    </article>

    <style>
        .nav-chart {
            border-bottom: 0;
        }

        .nav-chart li a{
            padding: 5px!important;
            font-size: 14px!important;
            width: inherit;
            border-radius: 2px;
            border: solid 1px #DDDDDD;
            min-height: inherit;
        }

    </style>

@endsection
