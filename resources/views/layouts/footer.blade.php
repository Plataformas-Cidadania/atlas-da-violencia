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

<?php
$menus_sobre = DB::table('quemsomos')->where('idioma_sigla', $lang)->where('tipo', 1)->where('origem_id', 1)->orderBy('posicao')->get();
?>
<footer id="iniciodorodape" class="container-fluid  hidden-print" role="contentinfo" ng-class="{'alto-contraste': altoContrasteAtivo}">
    <div class="text-right"><a href="<?php if($rota!='/'){?>{{$rota}}<?php }?>#iniciodoconteudo"><i class="fa fa-chevron-circle-up" aria-hidden="true" accesskey="9"></i> @lang('links.back-top') </a><br><br></div>



    <div class="row bg-qui" ng-class="{'alto-contraste': altoContrasteAtivo}">
        <div class="container">
            <br>
            <div class="row">
                <div class="col-md-3">
                    <div class=" menu-box-rp">
                        <h3>@lang('pages.rp-collections')</h3>
                        <ul class="menu-rp">
                            @foreach($links as $link)
                                <li>
                                    <a href="filtros-series/{{$link->link}}/{{clean($link->titulo)}}">
                                        {{$link->titulo}}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class=" menu-box-rp">
                        <h3>@lang('pages.rp-services')</h3>
                        <ul class="menu-rp">
                            <li><a href="contato">@lang('links.contact')</a></li>
                            <li><a href="noticias">@lang('links.news')</a></li>
                            <li><a href="artigos/0/todos">@lang('links.articles')</a></li>
                            <li><a href="videos">@lang('links.videos')</a></li>
                        </ul>
                    </div>
                </div>
                @if($setting->twitter!="" || $setting->youtube!="" || $setting->facebook!="" || $setting->pinterest!="" || $setting->google!="")
                <div class="col-md-3">
                    <div class=" menu-box-rp">
                        <h3>@lang('pages.rp-networks')</h3>
                        <ul class="menu-rp">
                            @if($setting->twitter!="")<li><a href="{{$setting->twitter}}" target="_blank">Twitter</a></li>@endif
                            @if($setting->youtube!="")<li><a href="{{$setting->youtube}}" target="_blank">YouTube</a></li>@endif
                            @if($setting->facebook!="")<li><a href="{{$setting->facebook}}" target="_blank">Facebook</a></li>@endif
                            @if($setting->pinterest!="")<li><a href="{{$setting->pinterest}}" target="_blank">Pinterest</a></li>@endif
                            @if($setting->google!="")<li><a href="{{$setting->google}}" target="_blank">Google Plus</a></li>@endif
                        </ul>
                    </div>
                </div>
                @endif
                <div class="col-md-3">
                    <div class=" menu-box-rp">
                        <h3>@lang('pages.rp-about')</h3>
                        <ul class="menu-rp">
                            <li><a href="quem/3/sobre">Sobre</a></li>
                            <li><a href="quem/4/equipe">Equipe</a></li>
                            <li><a href="parceiros">Parceiros</a></li>
                            <li><a href="contato">Contato</a></li>
                            <li><a href="pg/25/mapa-do-site">Mapa do Site</a></li>
                            <li><a href="pg/26/perguntas-frequentes">Perguntas Frequentes</a></li>
                            <li><a href="quem/5/glossario">Glossário</a></li>
                            <li><a href="acessibilidade">@lang('links.accessibility')</a></li>
                            <li><a href="api">API</a></li>
                            {{--@foreach($menus_sobre as $menu)
                                <li role="presentation"><a href="quem/{{$menu->id}}/{{clean($menu->titulo)}}" style="clear: both;">{{$menu->titulo}}</a></li>
                            @endforeach--}}
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