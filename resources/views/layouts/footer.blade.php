<style>
    div#wrapper-barra-brasil{
        max-width: 1170px;
    }
    div#wrapper-footer-brasil{
        max-width: 1170px;
    }
    #footer-brasil {
        background: none repeat scroll 0% 0% #0042b1;
        padding: 1em 0px;
        max-width: 100%;
    }
    /*#footer-brasil {
        background: none repeat scroll 0% 0% #00420c;
        padding: 1em 0px;
        max-width: 100%;
    }*/


</style>
<footer id="iniciodorodape" class="container-fluid  hidden-print" role="contentinfo" ng-class="{'alto-contraste': altoContrasteAtivo}">
    <div class="text-right"><a href="#iniciodoconteudo"><i class="fa fa-chevron-circle-up" aria-hidden="true" accesskey="9"></i> @lang('links.back-top') </a><br><br></div>



    <div class="row bg-qui" ng-class="{'alto-contraste': altoContrasteAtivo}">
        <div class="container">
            <br>
            <div class="row">
                <div class="col-md-3">
                    <div class=" menu-box-rp">
                        <h3>Assuntos</h3>
                        <ul class="menu-rp">
                            @foreach($links as $link)
                                <li>
                                    <a href="filtros/{{$link->link}}/{{clean($link->titulo)}}">
                                        {{$link->titulo}}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class=" menu-box-rp">
                        <h3>Serviços</h3>
                        <ul class="menu-rp">
                            <li><a href="contato">@lang('links.contact')</a></li>
                            <li><a href="noticias">@lang('links.news')</a></li>
                            <li><a href="artigos/0/todos">@lang('links.articles')</a></li>
                            <li><a href="videos">@lang('links.videos')</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class=" menu-box-rp">
                        <h3>Redes sociais</h3>
                        <ul class="menu-rp">
                            <li><a href="{{$setting->twitter}}" target="_blank">Twitter</a></li>
                            <li><a href="{{$setting->youtube}}" target="_blank">YouTube</a></li>
                            <li><a href="{{$setting->facebook}}" target="_blank">Facebook</a></li>
                            <li><a href="{{$setting->pinterest}}" target="_blank">Google Plus</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class=" menu-box-rp">
                        <h3>Sobre o site</h3>
                        <ul class="menu-rp">
                            <li><a href="acessibilidade">@lang('links.accessibility')</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <br>
        <br>
    </div>
    <div class="row">
        <div id="footer-brasil"></div>
    </div>
    <br>
    <div class="container text-center">
        <p ng-class="{'alto-contraste': altoContrasteAtivo}">@lang('pages.rights')</p>
    </div>
</footer>