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


<header class="row" id="iniciodoconteudo" class="row bg-pri hidden-print" role="banner">

    <div class="container  hidden-print">
        <div id="acessibilidade">
            <ul id="atalhos" class="col-md-6 col-sm-12">
                <li><a href="#iniciodoconteudo" ng-class="{'alto-contraste': altoContrasteAtivo}" accesskey="1">Ir para o conteúdo <span class="bg-sec btn-acessibilidade">1</span></a></li>
                <li><a href="#iniciodomenu" ng-class="{'alto-contraste': altoContrasteAtivo}" accesskey="2">Ir para o menu <span class="bg-sec btn-acessibilidade">2</span></a></li>
                {{--<li><a href="#busca">Ir para a busca <span class="bg-sec btn-acessibilidade">3</span></a></li>--}}
                <li><a href="#iniciodorodape" ng-class="{'alto-contraste': altoContrasteAtivo}" accesskey="4">Ir para o rodapé <span class="bg-sec btn-acessibilidade">4</span></a></li>
            </ul>
            <ul id="botoes" class="col-md-6 col-sm-12 text-right">
                <li><a href="/acessibilidade" ng-class="{'alto-contraste': altoContrasteAtivo}"><i class="fa fa-universal-access" aria-hidden="true"></i> Acessibilidade </a></li>
                <li><a href="#" id="bt_contraste" ng-click="setAltoContraste()" ng-class="{'alto-contraste': altoContrasteAtivo}"><i class="fa fa-adjust" aria-hidden="true"></i> Alto contraste</a></li>
                {{--<li><a href="mapa.html" ng-class="{'alto-contraste': altoContrasteAtivo}"> Mapa do site </a></li>--}}
                <li><img src="/img/portugues.jpg" alt=""></li>
                <li><img src="/img/ingles.jpg" alt=""></li>
            </ul>

        </div>
    </div>

    <div class="container-fluid row bg-qui  hidden-print">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <a href="/" class="logo">
                        <picture>
                            <source srcset="/imagens/settings/sm-{{$setting->imagem}}" media="(max-width: 468px)">
                            <source srcset="/imagens/settings/{{$setting->imagem}}" class="img-responsive">
                            <img srcset="/imagens/settings/{{$setting->imagem}}" alt="{{$setting->titulo}}" title="{{$setting->titulo}}">
                        </picture>
                    </a>
                </div>
                <div class="col-md-6"></div>
                <div class="col-md-3"></div>
            </div>
        </div>
    </div>



    <div class="visible-print-block"><h2>&nbsp;&nbsp; {{$setting->titulo}}</h2></div>
    <div class="line_title bg-pri"></div>
    <br>
</header>




