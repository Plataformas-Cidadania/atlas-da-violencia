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
</style>


<header  id="iniciodoconteudo" class="  hidden-print" role="banner">

    <div class="container  hidden-print">
        <div id="acessibilidade">
            <ul id="atalhos" class="col-md-6 col-sm-12">
                <li><a href="#iniciodoconteudo" ng-class="{'alto-contraste': altoContrasteAtivo}" accesskey="1">Ir para o conteúdo <span class="bg-sec btn-acessibilidade">1</span></a></li>
                <li><a href="#iniciodomenu" ng-class="{'alto-contraste': altoContrasteAtivo}" accesskey="2">Ir para o menu <span class="bg-sec btn-acessibilidade">2</span></a></li>
                {{--<li><a href="#busca">Ir para a busca <span class="bg-sec btn-acessibilidade">3</span></a></li>--}}
                <li><a href="#iniciodorodape" ng-class="{'alto-contraste': altoContrasteAtivo}" accesskey="4">Ir para o rodapé <span class="bg-sec btn-acessibilidade">4</span></a></li>
            </ul>
            <ul id="botoes" class="col-md-6 col-sm-12 text-right">
                <li><a href="acessibilidade" ng-class="{'alto-contraste': altoContrasteAtivo}"><i class="fa fa-universal-access" aria-hidden="true"></i> Acessibilidade </a></li>
                <li><a href="#" id="bt_contraste" ng-click="setAltoContraste()" ng-class="{'alto-contraste': altoContrasteAtivo}"><i class="fa fa-adjust" aria-hidden="true"></i> Alto contraste</a></li>
                {{--<li><a href="mapa.html" ng-class="{'alto-contraste': altoContrasteAtivo}"> Mapa do site </a></li>--}}
                <li><img src="img/portugues.jpg" alt=""></li>
                <li><img src="img/ingles.jpg" alt=""></li>
            </ul>

        </div>
    </div>

    <div class=" bg-qui  hidden-print"  ng-class="{'alto-contraste': altoContrasteAtivo}">
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
                <div class="col-md-3 col-sm-4 hidden-xs text-right col-md-offset-4 box-logo">
                    <a href="http://www.ipea.gov.br/" target="_blank"><img srcset="img/ipea.png" alt="ipea" title="ipea"></a>
                </div>
                <div class="col-md-2 col-sm-3 hidden-xs text-right box-logo">
                    <a href="http://www.forumseguranca.org.br/" target="_blank"><img srcset="img/fbsp.png" alt="Fórum Brasileiro de Segurança Pública" title="Fórum Brasileiro de Segurança Pública"></a>
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
                    <li role="presentation"><a href="http://{{$base_href}}" accesskey="h" @if($rota=='/') class="corrente" @endif>Início</a></li>
                    <li role="presentation"><a href="quem/3/sobre" accesskey="q" @if($rota=='quem') class="corrente" @endif>Sobre</a></li>
                    <li role="presentation"><a href="series" accesskey="q" @if($rota=='series') class="corrente" @endif>Pesquisas</a></li>
                    <li role="presentation"><a href="artigos/0/todos" accesskey="n" @if($rota=='artigos') class="corrente" @endif>Artigos</a></li>
                    <li role="presentation"><a href="videos" accesskey="q" @if($rota=='videos') class="corrente" @endif>Vídeos</a></li>
                    <li role="presentation"><a href="downloads" accesskey="q" @if($rota=='downloads') class="corrente" @endif>Downloads</a></li>
                    <li role="presentation"><a href="contato" accesskey="c" @if($rota=='contato') class="corrente" @endif>Contato</a></li>
                </ul>
            </nav>
        </nav>
    </div>
    <br>
    @if($rota=='/')
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                    <iframe width="100%" height="315" src="https://www.youtube.com/embed/@if(!empty($video)){{codigoYoutube($video->link_video)}}@endif" frameborder="0" allowfullscreen></iframe>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" style="padding: 25px; background-color: #ececec; height: 315px;"  ng-class="{'alto-contraste': altoContrasteAtivo}">
                    <h2 style="margin-top: 0;">{{$ultimaArtigo->titulo}}</h2>
                    <div style="height: 100px; overflow: hidden;">{!! $ultimaArtigo->descricao !!}</div>
                    <br>
                    <a href="noticia/{{$ultimaArtigo->id}}/{{clean($ultimaArtigo->titulo)}}" class="btn btn-info" >Mais Detalhes</a>
                </div>
            </div>
            <?php /*?>
            <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
                <!-- Indicators -->
                <ol class="carousel-indicators">
                    <?php $cont_itens_wd=0;?>
                    @foreach($webdoors as $webdoor)
                        <li data-target="#carousel-example-generic" data-slide-to="<?php echo $cont_itens_wd;?>" @if($cont_itens_wd==0) class="active" @endif></li>
                        <?php $cont_itens_wd++;?>
                    @endforeach
                </ol>

                <!-- Wrapper for slides -->
                <div class="carousel-inner">
                    <?php $cont=0;?>
                    @foreach($webdoors as $webdoor)

                        <a @if($webdoor->link!="")
                           href="{{$webdoor->link}}"
                           @elseif($webdoor->descricao!="")
                           href="/webdoor/{{$webdoor->id}}"
                           @endif
                           class="item @if($cont==0) active @endif">
                            <picture>
                                <source srcset="/imagens/webdoors/sm-{{$webdoor->imagem}}" media="(max-width: 468px)">
                                <source srcset="/imagens/webdoors/md-{{$webdoor->imagem}}" media="(max-width: 768px)">
                                <source srcset="/imagens/webdoors/lg-{{$webdoor->imagem}}" class="img-responsive">
                                <img srcset="/imagens/webdoors/lg-{{$webdoor->imagem}}" alt="{{$webdoor->titulo}}" title="{{$webdoor->titulo}}" >
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
            <?php */?>
            <div class="marking bg-qua"></div>
        </div>
    @endif
</header>




