@extends('.layout')
@section('title', trans('links.articles'))
@section('content')

    <style>
        fieldset.border {
            border: solid 1px #ddd !important;
            padding: 0 1.4em 1.4em 1.4em !important;
            margin: 0 0 1.5em 0 !important;
        }

        legend.border {
            color: #505050;
            font-size: 1.2em !important;
            font-weight: bold !important;
            text-align: left !important;
            width:auto;
            padding:0 10px;
            border-bottom:none;
        }
    </style>

    {{--{{ Counter::count('artigo') }}--}}
    <div class="container" ng-controller="artigosCtrl" ng-init="dadosParametrosBusca()">
        <div class="row">
            <div class="col-md-12">

                <h1>@lang('links.articles2')</h1>
                <div class="line_title bg-pri"></div>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-12">
                <fieldset class="border">
                    <legend class="border">Busca</legend>
                    <form class="form" name="frmBusca" id="frmBusca" action="busca-artigos-v2" onsubmit="return submitForm()" method="post">
                        <input type="hidden" name="assunto_id" id="assunto_id" ng-model="assunto_id">

                        {!! csrf_field() !!}
                        <div class="row">
                            <div class="col-md-3">
                                <label for="busca">Título</label>
                                <input type="text" class="form-control" id="busca" name="busca" ng-model="busca" placeholder="Digite uma palavra do título">
                            </div>
                            <div class="col-md-3">
                                <label for="ano">Autor</label>
                                <input type="text" class="form-control" name="autorName" id="autorName"  onkeyup="searchAutores()">
                                <div class="div-info" id="divAutores" style="display: none;">
                                    <div ng-repeat="autor in autores">{autor.titulo}</div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label for="ano">Ano</label>
                                <select name="ano" id="ano" class="form-control" ng-options="ano for ano in anos track by ano" ng-model="ano" ng-init="ano='Todos'">
                                </select>
                            </div>
                            <input type="hidden" id="publicacaoAtlas" name="publicacaoAtlas" ng-model="publicacaoAtlas">
                            {{--<div class="col-md-2">
                                <br>
                                <label for="publicacaoAtlas">
                                    <input type="checkbox" id="publicacaoAtlas" name="publicacaoAtlas" value="1" @if($publicacaoAtlasBusca==1) checked @endif
                                           style="width: 20px; height: 20px; margin: 0 10px 0 0; top: 15px; position: relative; float: left;">
                                    <div style="float: left; padding-top: 15px;">Atlas Violência</div>
                                </label>
                            </div>--}}

                            <div class="col-md-1">
                                <button type="text" class="btn btn-info" onClick="searchArticles()" style="margin: 25px 0 0 0;">Pesquisar</button>
                            </div>
                        </div>
                    </form>
                </fieldset>
            </div>
        </div>

        <div class="row">
            <br>


            {{--<div class="col-md-offset-9 col-md-3 text-right">
                <form class="form-inline" action="busca-artigos/{{$origem_id}}/lista" method="post">
                    {!! csrf_field() !!}
                    <div class="form-group">
                        <label class="sr-only" for="exampleInputAmount">Busca</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="busca" name="busca" placeholder="@lang('forms.search')">
                            <div class="input-group-addon">
                                <button type="submit" value="busca-artigos" style="border: 0; background-color: inherit;"><i class="fa fa-search" aria-hidden="true"></i></button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>--}}
        </div>
        <div class="row">
            <div class="col-md-3 col-sm-3">
                <br>
                <h4 style="margin-left: 4px;">Assuntos</h4>
                <ul class="menu-vertical ">
                    <li role="presentation" ng-repeat="assunto in assuntos">
                        <a ng-click="searchByAssunto(assunto.id)" accesskey="q" ng-class="{'menu-vertical-marcado': (assunto.id==assuntoId)}" style="cursor:pointer; clear: both;">
                            <i class="fa fa-dot-circle-o" aria-hidden="true"></i>
                            <% assunto.titulo %>
                            <span style="float: right;">(<% assunto.qtd %>>)</span>
                        </a>
                    </li>
                </ul>

                <div class="text-right" ng-show="assuntoId > 0">
                    <a  class="text-danger" onclick="searchByAssunto(0)" style="cursor: pointer;"> <i class="fa fa-times" aria-hidden="true"></i> Remover filtro</a>
                </div>

                <br>

            </div>
            <div class="col-md-9 col-sm-9">
        
            <div class="row" id="artigo_<% index %>" ng-repeat="(index, artigo) in artigos">
                <a href="artigo/<% artigo.id %>/<% artigo.titulo %>">
                    <div class="col-md-3 col-sm-3" ng-show="artigo.imagem!=''">
                        <picture>
                            <source srcset="imagens/artigos/sm-<% artigo.imagem %>" media="(max-width: 468px)">
                            <source srcset="imagens/artigos/md-<% artigo.imagem %>" media="(max-width: 768px)">
                            <source srcset="imagens/artigos/sm-<% artigo.imagem %>" class="img-responsive">
                            <img srcset="imagens/artigos/sm-<% artigo.imagem %>" alt="Imagem sobre <% artigo.titulo %>," title="Imagem sobre <% artigo.titulo %>," class="align-img" width="100%">
                        </picture>
                    </div>
                    <div  class="descricao-publicaco" ng-class="{'col-md-9': artigo.imagem=='','col-sm-9': artigo.imagem=='', 'col-md-12': artigo.imagem!='','col-sm-12': artigo.imagem!=''}">
                        <h2><% artigo.titulo %></h2>
                        <p><% artigo.descricao %></p>
                        <button class="btn btn-none">@lang('buttons.keep-reading')  </button>
                    </div>
                </a>
                <hr>
            </div>
            
        

        {{--<div>{{ $artigos->links() }}</div>--}}
        {{--@if(count($artigos) < $totalArtigos-1)
        <div class="text-center">
            <button class="btn btn-info" onclick="loadMore({{$take+1}})">
                Veja Mais Publicações
            </button>
        </div>
        @endif--}}

        </div>
        </div>

    </div>
@endsection
