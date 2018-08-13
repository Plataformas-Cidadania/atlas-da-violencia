<div id="barra-brasil" class="hidden-print" style="background:#7F7F7F; height: 20px; padding:0 0 0 10px;display:block;" aria-hidden="true">
    <ul id="menu-barra-temp" style="list-style:none;">
        <li style="display:inline; float:left;padding-right:10px; margin-right:10px; border-right:1px solid #EDEDED"><a href="http://brasil.gov.br" style="font-family:sans,sans-serif; text-decoration:none; color:white;">Portal do Governo Brasileiro</a></li>
        <li><a style="font-family:sans,sans-serif; text-decoration:none; color:white;" href="http://epwg.governoeletronico.gov.br/barra/atualize.html">Atualize sua Barra de Governo</a></li>
    </ul>
</div>

<style>
    .busca-select {
        float: left;
        margin: -32px 0 0 3px;
        background-color: inherit;
        border: 0;
        width: 150px;
        font-size: 14px;
        padding: 5px;
        color: inherit;
        outline:none !important;
        border: none;
    }
    .busca{
        padding:0 35px 0 160px;
    }
    .busca-icon{
        float: right;
        margin: -30px 10px 0 0;
        font-size: 25px;
    }
    .box-destaque{
        padding: 25px;
        background-color: #ececec;
        min-height: 315px;
    }

</style>


<header  id="iniciodoconteudo" class="  hidden-print" role="banner">

    <div class="container  hidden-print">
        <div id="acessibilidade">
            <ul id="atalhos" class="col-md-6 col-sm-12">
                <li><a href="#iniciodoconteudo" ng-class="{'alto-contraste': altoContrasteAtivo}" accesskey="1">@lang('links.skip-content') <span class="bg-sec btn-acessibilidade">1</span></a></li>
                <li><a href="#iniciodomenu" ng-class="{'alto-contraste': altoContrasteAtivo}" accesskey="2">@lang('links.go-menu') <span class="bg-sec btn-acessibilidade">2</span></a></li>
                {{--<li><a href="#busca">Ir para a busca <span class="bg-sec btn-acessibilidade">3</span></a></li>--}}
                <li><a href="#iniciodorodape" ng-class="{'alto-contraste': altoContrasteAtivo}" accesskey="4">@lang('links.go-footer') <span class="bg-sec btn-acessibilidade">4</span></a></li>
            </ul>
            <ul id="botoes" class="col-md-6 col-sm-12 text-right">
                <li><a href="acessibilidade" ng-class="{'alto-contraste': altoContrasteAtivo}"><i class="fa fa-universal-access" aria-hidden="true"></i> @lang('links.accessibility') </a></li>
                <li><a href="#" id="bt_contraste" ng-click="setAltoContraste()" ng-class="{'alto-contraste': altoContrasteAtivo}"><i class="fa fa-adjust" aria-hidden="true"></i> @lang('links.high-contrast')</a></li>
                {{--<li><a href="mapa.html" ng-class="{'alto-contraste': altoContrasteAtivo}"> Mapa do site </a></li>
                <li><a href="lang/pt_BR"><img src="img/portugues.jpg" alt=""></a></li>
                <li><a href="lang/en"><img src="img/ingles.jpg" alt=""></a></li>
                --}}

                @foreach($idiomas as $idioma)
                    <li><a href="lang/{{$idioma->sigla}}"><img src="imagens/idiomas/xs-{{$idioma->imagem}}" alt=""></a></li>
                @endforeach
            </ul>

        </div>
    </div>

    <div class=" bg-qui hidden-print"  ng-class="{'alto-contraste': altoContrasteAtivo}">
        <div class="container">
            <div class="row">
                <div class="col-md-3 col-sm-5">
                    <a href="http://{{$base_href}}" class="logo">
                        <picture>
                            <source srcset="imagens/settings/sm-{{$setting->imagem}}" media="(max-width: 468px)">
                            <source srcset="imagens/settings/{{$setting->imagem}}" class="img-responsive">
                            <img srcset="imagens/settings/{{$setting->imagem}}" alt="{{$setting->titulo}}" title="{{$setting->titulo}}">
                        </picture>
                    </a>
                </div>
                <div class="col-md-5 col-sm-7 hidden-xs text-right col-md-offset-4 box-logo">
                    @foreach($apoios as $apoio)
                    <a href="{{$apoio->url}}" target="_blank"><img srcset="imagens/apoios/{{$apoio->imagem}}" alt="ipea" title="ipea" height="51" style="margin-left: 50px;"></a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>



    <div class="visible-print-block"><h2>&nbsp;&nbsp; {{$setting->titulo}}</h2></div>
    <div class="line_title bg-pri"></div>

    <div class="container">
        <nav class="navbar navbar-default">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>
            <nav class="menu-position collapse navbar-collapse" id="bs-example-navbar-collapse-1" role="navigation" >
                <ul id="iniciodomenu" class="nav nav-pills nav-justified">
                    <li role="presentation"><a href="http://{{$base_href}}" accesskey="h" @if($rota=='/') class="corrente" @endif>@lang('links.home')</a></li>
                   {{-- @foreach($menusQuem as $menuQuem)
                        <li role="presentation"><a href="quem/{{$menuQuem->id}}/{{clean($menuQuem->titulo)}}" accesskey="q" @if($rota=='quem') class="corrente" @endif>@lang('links.about')</a></li>
                    @endforeach--}}
                    <li role="presentation"><a href="quem" accesskey="q" @if($rota=='quem') class="corrente" @endif>@lang('links.about')</a></li>

                    {{--<li role="presentation"><a href="series" accesskey="q" @if($rota=='series') class="corrente" @endif>@lang('links.researches')</a></li>--}}

                    <li role="presentation"><a href="filtros-series" accesskey="q" @if($rota=='series') class="corrente" @endif>@lang('links.researches')</a></li>
                    <li role="presentation"><a href="indicadores" accesskey="n">Indicadores</a></li>

                    <li role="presentation"><a href="artigos/0/todos" accesskey="n" @if($rota=='artigos/{origem_id}/{titulo}') class="corrente" @endif>@lang('links.articles')</a></li>
                    <li role="presentation"><a href="videos" accesskey="q" @if($rota=='videos') class="corrente" @endif>@lang('links.videos')</a></li>
                    <li role="presentation"><a href="downloads" accesskey="q" @if($rota=='downloads') class="corrente" @endif>@lang('links.downloads')</a></li>
                    <li role="presentation"><a href="contato" accesskey="c" @if($rota=='contato') class="corrente" @endif>@lang('links.contact')</a></li>
                </ul>
            </nav>
        </nav>
    </div>
    <br>
    @if($rota=='/')
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" ng-init="showVideo=false">
                    <iframe width="100%" height="315" src="https://www.youtube.com/embed/@if(!empty($video)){{codigoYoutube($video->link_video)}}@endif" frameborder="0" allowfullscreen></iframe>

                    {{--<img ng-src='img/video.png' ng-show="!showVideo" ng-click="showVideo=true" style="cursor:pointer;">
                    <video width="100%" controls ng-if="showVideo" autoplay>
                        <source src="filevideos/institucional.mp4" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>--}}
                </div>
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 box-destaque"   ng-class="{'alto-contraste': altoContrasteAtivo}">





                    <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
                <!-- Indicators -->
                <ol class="carousel-indicators">
                    <?php $cont_itens_wd=2;?>
                        <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
                        <li data-target="#carousel-example-generic" data-slide-to="1"></li>
                    @foreach($webdoors as $webdoor)
                        <li data-target="#carousel-example-generic" data-slide-to="<?php echo $cont_itens_wd;?>" @if($cont_itens_wd==0) class="active" @endif></li>
                        <?php /*?><li data-target="#carousel-example-generic" data-slide-to="<?php echo $cont_itens_wd;?>" @if($cont_itens_wd==0) class="active" @endif></li><?php */?>
                        <?php $cont_itens_wd++;?>
                    @endforeach


                </ol>

                <!-- Wrapper for slides -->
                <div class="carousel-inner">
                    {{--GRAFICO--}}
                    <a href="" class="item active" style="margin-top: -30px;">
                        <div style="width:100%;">
                            <canvas id="canvas" height="160"></canvas>
                        </div>
                    </a>
                    {{----}}
                    {{--TEXTO--}}
                    <a href="" class="item" style="background-color: #ececec;">
                        <h2 style="margin-top: 0;">{{$ultimaArtigo->titulo}}</h2>
                        <div style="height: 100px; overflow: hidden;">{!! substr(strip_tags($ultimaArtigo->descricao), 0, 600)."..." !!}</div>
                        <br>
                        <div href="artigo/{{$ultimaArtigo->id}}/{{clean($ultimaArtigo->titulo)}}" class="btn btn-info" >@lang('buttons.more-details')</div>
                    </a>
                    {{----}}
                    {{--IMAGEM--}}
                    <?php $cont=2;?>
                    @foreach($webdoors as $webdoor)

                        <a @if($webdoor->link!="")
                           href="{{$webdoor->link}}"
                           @elseif($webdoor->descricao!="")
                           href="webdoor/{{$webdoor->id}}"
                           @endif
                           class="item">
                                <?php /*?>class="item @if($cont==0) active @endif"><?php */?>
                            <picture>
                                <source srcset="imagens/webdoors/sm-{{$webdoor->imagem}}" media="(max-width: 468px)">
                                <source srcset="imagens/webdoors/md-{{$webdoor->imagem}}" media="(max-width: 768px)">
                                <source srcset="imagens/webdoors/lg-{{$webdoor->imagem}}" class="img-responsive">
                                <img srcset="imagens/webdoors/lg-{{$webdoor->imagem}}" alt="{{$webdoor->titulo}}" title="{{$webdoor->titulo}}" width="100%" height="260">
                                {{--@if(!empty($webdoor->resumida))
                                    <div class="carousel-caption">
                                        <h3 ng-class="{'alto-contraste': altoContrasteAtivo}">{{$webdoor->titulo}}</h3>
                                        <p ng-class="{'alto-contraste': altoContrasteAtivo}">{{$webdoor->resumida}}</p>
                                    </div>
                                @endif--}}
                            </picture>
                        </a>

                        <?php $cont++;?>
                    @endforeach
                    {{----}}


                </div>

                <!-- Controls -->
                <a class="left carousel-control hidden-xs" href="#carousel-example-generic" role="button" data-slide="prev">
                    <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="right carousel-control hidden-xs" href="#carousel-example-generic" role="button" data-slide="next">
                    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
            </div>








                </div>
            </div>

            <div class="marking bg-qua"></div>
        </div>


        {{----}}

        <style>
            canvas{
                -moz-user-select: none;
                -webkit-user-select: none;
                -ms-user-select: none;
            }
        </style>

        {{----}}




    @endif
</header>




