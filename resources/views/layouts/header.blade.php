<?php
$menus = DB::table('menu')->where('status', 1)->where('idioma_sigla', $lang)->orderBy('posicao')->get();
$modulos_menu = DB::table('menu')->where('idioma_sigla', $lang)->orderBy('posicao')->get();
$setting = DB::table('settings')->orderBy('id', 'desc')->first();
$lang = App::getLocale();
$series = \App\Serie::join('textos_series', 'series.id', '=', 'textos_series.serie_id')
    ->where('series.id', $setting->serie_id)
    ->where('textos_series.idioma_sigla', $lang)
    ->first();

$valorTeste =  "1.5699925";

function convertMedida($valorTeste){

}
$qtr = strlen($valorTeste);
if($qtr==6){
    $medida = "K";

}elseif($qtr==9){
    $medida = "M";
}elseif($qtr==12){
    $medida = "B";
}

//echo round($valorTeste,2).$medida;



?>
<div id="barra-brasil" class="hidden-print" style="background:#7F7F7F; height: 20px; padding:0 0 0 10px;display:block;"
     aria-hidden="true">
    <ul id="menu-barra-temp" style="list-style:none;">
        <li style="display:inline; float:left;padding-right:10px; margin-right:10px; border-right:1px solid #EDEDED"><a
                    href="http://brasil.gov.br" style="font-family:sans,sans-serif; text-decoration:none; color:white;">Portal
                do Governo Brasileiro</a></li>
        <li><a style="font-family:sans,sans-serif; text-decoration:none; color:white;"
               href="http://epwg.governoeletronico.gov.br/barra/atualize.html">Atualize sua Barra de Governo</a></li>
    </ul>
</div>


<header id="iniciodoconteudo" class="  hidden-print" role="banner">

    <div class="container  hidden-print">
        <div id="acessibilidade">
            <ul id="atalhos" class="col-md-6 col-sm-12">
                <li><a href="<?php if($rota != '/'){?>{{$rota}}<?php }?>#iniciodoconteudo"
                       ng-class="{'alto-contraste': altoContrasteAtivo}" accesskey="1">@lang('links.skip-content') <span
                                class="bg-sec btn-acessibilidade">1</span></a></li>
                <li><a href="<?php if($rota != '/'){?>{{$rota}}<?php }?>#iniciodomenu"
                       ng-class="{'alto-contraste': altoContrasteAtivo}" accesskey="2">@lang('links.go-menu') <span
                                class="bg-sec btn-acessibilidade">2</span></a></li>
                {{--<li><a href="#busca">Ir para a busca <span class="bg-sec btn-acessibilidade">3</span></a></li>--}}
                <li><a href="<?php if($rota != '/'){?>{{$rota}}<?php }?>#iniciodorodape"
                       ng-class="{'alto-contraste': altoContrasteAtivo}" accesskey="4">@lang('links.go-footer') <span
                                class="bg-sec btn-acessibilidade">4</span></a></li>
            </ul>
            <ul id="botoes" class="col-md-6 col-sm-12 text-right">
                <li><a href="acessibilidade" ng-class="{'alto-contraste': altoContrasteAtivo}"><i
                                class="fa fa-universal-access" aria-hidden="true"></i> @lang('links.accessibility') </a>
                </li>
                <li><a id="bt_contraste" ng-click="setAltoContraste()" ng-class="{'alto-contraste': altoContrasteAtivo}"
                       style="cursor: pointer;"><i class="fa fa-adjust"
                                                   aria-hidden="true"></i> @lang('links.high-contrast')</a></li>

                @foreach($idiomas as $idioma)
                    <li><a href="lang/{{$idioma->sigla}}"><img src="imagens/idiomas/xs-{{$idioma->imagem}}"
                                                               alt="{{$idioma->sigla}}" title="{{$idioma->sigla}}"></a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    <div class=" bg-qui hidden-print" ng-class="{'alto-contraste': altoContrasteAtivo}">
        <div class="container">
            <div class="row">
                <div class="col-md-3 col-sm-5">
                    <a href="http://{{$base_href}}" class="logo">
                        <picture>
                            <source srcset="imagens/settings/sm-{{$setting->imagem}}" media="(max-width: 468px)">
                            <source srcset="imagens/settings/{{$setting->imagem}}" class="img-responsive">
                            <img srcset="imagens/settings/{{$setting->imagem}}" alt="{{$setting->titulo}}"
                                 title="{{$setting->titulo}}">
                        </picture>
                    </a>
                </div>
                <div class="col-md-5 col-sm-7 hidden-xs text-right col-md-offset-4 box-logo">
                    @foreach($apoios as $apoio)
                        <a href="{{$apoio->url}}" target="_blank"><img srcset="imagens/apoios/{{$apoio->imagem}}"
                                                                       alt="ipea" title="ipea" height="51"
                                                                       style="margin-left: 50px;"></a>
                    @endforeach
                </div>
            </div>
            <style>
                .menu-header a{
                    margin: 10px 5px !important;
                    padding: 0 !important;
                }
                .menu-header li a:hover{
                    background-color: inherit !important;
                    color: inherit !important;
                }
                .menu-header li a:focus{
                    background-color: {{$setting->cor5}} !important;
                    color: inherit !important;
                }
                .menu-header li a:active{
                    background-color: inherit !important;
                    color: inherit !important;
                }
            </style>
            <div class="row" style="position:relative; z-index: 99999999;">
                <div class="col-md-4">
                    <nav class="menu-position" id="bs-example-navbar-collapse-1" role="navigation">
                        <ul class="nav navbar-nav menu-header">
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Quem Somos <span class="caret"></span></a>
                                <ul class="dropdown-menu" aria-labelledby="quem-somos" >
                                    <li><a href="quem/3/sobre">Sobre</a></li>
                                    <li><a href="quem/4/equipe">Equipe</a></li>
                                    <li><a href="parceiros">Parceiros</a></li>
                                    <li><a href="contato">Contato</a></li>
                                </ul>
                            </li>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle text-right" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Ajuda <span class="caret"></span></a>
                                <ul class="dropdown-menu" aria-labelledby="ajuda">
                                    <li><a href="pg/25/mapa-do-site">Mapa do Site</a></li>
                                    <li><a href="pg/26/perguntas-frequentes">Perguntas Frequentes</a></li>
                                    <li><a href="quem/5/glossario">Glossário</a></li>
                                    <li><a href="acessibilidade">Acessibilidade</a></li>
                                    <li><a href="api">API</a></li>
                                </ul>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>


    <div class="visible-print-block"><h2>&nbsp;&nbsp; {{$setting->titulo}}</h2></div>
    <div class="line_title bg-pri"></div>

    <div class="container">
        <nav class="navbar navbar-default">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                        data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>
            <nav class="menu-position collapse navbar-collapse" id="bs-example-navbar-collapse-1" role="navigation">
                <ul id="iniciodomenu" class="nav nav-pills nav-justified">
                    @foreach($menus as $menu)
                        <li role="presentation"><a href="{{$menu->url}}" accesskey="{{$menu->accesskey}}"
                                                   @if($rota==$menu->url) class="corrente" @endif>{{$menu->title}}</a>
                    @endforeach


                    {{--<li role="presentation"><a href="http://{{$base_href}}" accesskey="h" @if($rota=='/') class="corrente" @endif>@lang('links.home')</a></li>
                    <li role="presentation"><a href="quem" accesskey="q" @if($rota=='quem') class="corrente" @endif>@lang('links.about')</a></li>
                    <li role="presentation"><a href="filtros-series" accesskey="q" @if($rota=='filtros-series') class="corrente" @endif>@lang('links.researches')</a></li>
                    @if($indicadores)
                    <li role="presentation"><a href="indicadores" accesskey="n" @if($rota=='indicadores') class="corrente" @endif>@lang('links.indicators')</a></li>
                    @endif
                    <li role="presentation"><a href="artigos/0/todos" accesskey="n" @if($rota=='artigos/{origem_id}/{titulo}') class="corrente" @endif>@lang('links.articles')</a></li>
                    <li role="presentation"><a href="videos" accesskey="q" @if($rota=='videos') class="corrente" @endif>@lang('links.videos')</a></li>
                    <li role="presentation"><a href="downloads" accesskey="q" @if($rota=='downloads') class="corrente" @endif>@lang('links.downloads')</a></li>
                    <li role="presentation"><a href="contato" accesskey="c" @if($rota=='contato') class="corrente" @endif>@lang('links.contact')</a></li>--}}
                </ul>
            </nav>
        </nav>
    </div>
    <br>
    @if($rota=='/')
        <div class="container">
            <?php
                $col_video = $setting->carousel==1 ? 6 : 12;
                $col_carousel = $setting->video_home==1 ? 6 : 12;
                $height_artigo = $setting->video_home==1 ? '130px' : '480px';
            ?>
            <div class="row">
                @if($setting->video_home==1)
                    <div class="col-xs-12 col-sm-{{$col_video}} col-md-{{$col_video}} col-lg-{{$col_video}}" ng-init="showVideo=false">
                        <iframe width="100%" height="315"
                                src="https://www.youtube.com/embed/@if(!empty($video)){{codigoYoutube($video->link_video)}}@endif"
                                frameborder="0" allowfullscreen></iframe>
                    </div>
                @endif
                @if($setting->carousel==1)
                    <div class="col-xs-12 col-sm-{{$col_carousel}} col-md-{{$col_carousel}} col-lg-{{$col_carousel}} box-destaque"
                     ng-class="{'alto-contraste': altoContrasteAtivo}">

                    <div id="carousel1" class="carousel slide" data-ride="carousel">
                        <!-- Indicators -->
                        <ol class="carousel-indicators">
                            <?php $cont_itens_wd = 0;?>
                            {{--<li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
                            <li data-target="#carousel-example-generic" data-slide-to="1"></li>--}}
                            @foreach($webdoors as $webdoor)
                                <li data-target="#carousel1" data-slide-to="<?php echo $cont_itens_wd;?>"
                                    @if($cont_itens_wd==0) class="active" @endif></li>
                                <?php /*?><li data-target="#carousel-example-generic" data-slide-to="<?php echo $cont_itens_wd;?>" @if($cont_itens_wd==0) class="active" @endif></li><?php */?>
                                <?php $cont_itens_wd++;?>
                            @endforeach


                        </ol>

                        <!-- Wrapper for slides -->
                        <div class="carousel-inner">

                            {{--GRAFICO--}}
                            <?php /* ?>
                            <a href="" class="item active" style="margin-top: -30px;">
                                <div style="width:100%;">
                                    @if(!empty($series) || $setting->dados_serie_home == 1)
                                        <h2 class="canvas-titulo">{{$setting->titulo_serie_home}}</h2>
                                        <canvas id="canvas" height="155"></canvas>
                                    @endif
                                </div>
                            </a>
                            {{----}}
                            <?php */ ?>
                            <?php /* ?>
                            @if(!empty($ultimaPostagem))
                                {{--TEXTO--}}
                                <a href="{{$rotaUltimaPostagem}}/{{$ultimaPostagem->id}}/{{clean($ultimaPostagem->titulo)}}"
                                   class="item" style="background-color: #ececec;">
                                    <h2 style="margin-top: 0;">{{$ultimaPostagem->titulo}}</h2>
                                    <div style="height: {{$height_artigo}}; overflow: hidden;">{!! substr(strip_tags($ultimaPostagem->descricao), 0, 600)."..." !!}</div>
                                    <br>
                                    <div class="btn btn-info">@lang('buttons.more-details')</div>
                                </a>
                                {{----}}
                            @endif
                            <?php */ ?>
                            {{--IMAGEM--}}
                            <?php $cont = 0;?>
                            @foreach($webdoors as $webdoor)

                                <a @if($webdoor->link!="")
                                   href="{{$webdoor->link}}"
                                   @elseif($webdoor->descricao!="")
                                   href="webdoor/{{$webdoor->id}}"
                                   @endif
                                   class="item @if($cont==0) active @endif">
                                    <picture>
                                        <source srcset="imagens/webdoors/sm-{{$webdoor->imagem}}"
                                                media="(max-width: 468px)">
                                        <source srcset="imagens/webdoors/md-{{$webdoor->imagem}}"
                                                media="(max-width: 768px)">
                                        <source srcset="imagens/webdoors/lg-{{$webdoor->imagem}}"
                                                class="img-responsive">
                                        <img srcset="imagens/webdoors/lg-{{$webdoor->imagem}}"
                                             alt="{{$webdoor->titulo}}" title="{{$webdoor->titulo}}" width="100%"
                                             >
                                        @if(!empty($webdoor->resumida))
                                            <div class="carousel-caption">
                                                <h3 ng-class="{'alto-contraste': altoContrasteAtivo}">{{$webdoor->titulo}}</h3>
                                                <p ng-class="{'alto-contraste': altoContrasteAtivo}">{{$webdoor->resumida}}</p>
                                            </div>
                                        @endif
                                    </picture>
                                </a>

                                <?php $cont++;?>
                            @endforeach
                            {{----}}


                        </div>

                        <!-- Controls -->
                        <a class="left carousel-control hidden-xs" href="#carousel1" role="button"
                           data-slide="prev">
                            <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="right carousel-control hidden-xs" href="#carousel1" role="button"
                           data-slide="next">
                            <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>
                    </div>

                </div>
                @endif
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
</header>




