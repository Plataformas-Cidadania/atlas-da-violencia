@extends('cms::layouts.app')

@section('content')
    {!! Html::script('/assets-cms/js/controllers/mensagemCtrl.js') !!}
<script>
    $(function () {
        $('[data-toggle="popover"]').popover()
    })
</script>
    <div ng-controller="mensagemCtrl" >
        <div class="box-padrao" ng-init="origem='{{$origem}}'">
            <h1><i class="fa fa-fw fa-newspaper-o"></i>&nbsp;Mensagens</h1>
            {{--<button class="btn btn-primary" ng-click="mostrarForm=!mostrarForm" ng-show="!mostrarForm">Novo Modulo</button>--}}
            <button class="btn btn-warning" ng-click="mostrarForm=!mostrarForm" ng-show="mostrarForm">Cancelar</button>
            <br><br>

        </div>

        <br>
        <div class="row">
            <div class="col-md-12">
                <div class="box-padrao">
                    <div class="input-group">
                        <div class="input-group-addon"><i class="fa fa-search" aria-hidden="true"></i></div>
                        <input class="form-control" type="text" ng-model="dadoPesquisa" placeholder="Faça sua busca"/>
                    </div>
                    <br>
                    <div><% mensagemMensagemr %></div>
                    <div ng-show="processandoListagem"><i class="fa fa-spinner fa-spin"></i> Processando...</div>
                    <h2 class="tabela_vazia" ng-show="!processandoListagem && totalItens==0">Nenhum registro encontrado!</h2>
                    <table ng-show="totalItens>0" class="table table-striped">
                        <thead>
                        <tr>
                            <th ng-click="ordernarPor('id')" style="mensagemr:pointer;">
                                Id
                                <i ng-if="ordem=='id' && sentidoOrdem=='asc'" class="fa fa-angle-double-down"></i>
                                <i ng-if="ordem=='id' && sentidoOrdem=='desc'" class="fa fa-angle-double-up"></i>
                            </th>

                            <th ng-click="ordernarPor('nome')" style="mensagemr:pointer;">
                                Nome
                                <i ng-if="ordem=='nome' && sentidoOrdem=='asc'" class="fa fa-angle-double-down"></i>
                                <i ng-if="ordem=='nome' && sentidoOrdem=='desc'" class="fa fa-angle-double-up"></i>
                            </th>
                            <th ng-click="ordernarPor('telefone')" style="mensagemr:pointer;">
                                Telefone
                                <i ng-if="ordem=='telefone' && sentidoOrdem=='asc'" class="fa fa-angle-double-down"></i>
                                <i ng-if="ordem=='telefone' && sentidoOrdem=='desc'" class="fa fa-angle-double-up"></i>
                            </th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr ng-repeat="mensagem in mensagens">
                            <td><% mensagem.id %></td>
                            <td><a href="cms/mensagem/<% mensagem.id %>" ng-class="mensagem.status == 1 ? 'mensagem-lida' : 'mensagem-nao-lida'" ng-click="status(mensagem.id);"><% mensagem.nome %></a></td>
                            <td><a href="cms/mensagem/<% mensagem.id %>" ng-class="mensagem.status == 1 ? 'mensagem-lida' : 'mensagem-nao-lida'" ng-click="status(mensagem.id);"><% mensagem.telefone %></a></td>


                            <td class="text-right">
                                <div>
                                    <a href="cms/mensagem/<% mensagem.id %>"><i class="fa fa-edit fa-2x" title="Editar" ng-click="status(mensagem.id);"></i></a>&nbsp;&nbsp;
                                    {{--<a  ng-class="mensagem.status == 1 ? 'color-success' : 'color-success-inactive'"  style="cursor: pointer;"><i class="fa fa-check-circle fa-2x" aria-hidden="true" ng-click="status(mensagem.id);"></i></a>&nbsp;&nbsp;--}}
                                    <a  ng-class="mensagem.status == 1 ? 'color-success' : 'color-success-inactive'"><i class="fa fa-check-circle fa-2x" aria-hidden="true"></i></a>&nbsp;&nbsp;
                                    <a><i data-toggle="modal" data-target="#modalExcluir" class="fa fa-remove fa-2x" ng-click="perguntaExcluir(mensagem.id, mensagem.nome, mensagem.imagem)"></i></a>
                                </div>
                            </td>
                            <td class="text-right"><a href="cms/mensagem/<% mensagem.id %>" ng-class="mensagem.status == 1 ? 'mensagem-lida' : 'mensagem-nao-lida'" ng-click="status(mensagem.id);"><% mensagem.created_at %></a></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <!--<button class="btn btn-primary btn-block" ng-click="loadMore()" ng-hide="currentPage==lastPage">Load More</button>-->
                <div ng-show="totalItens > 0" class="clan-paginacao">
                    <div class="item-paginacao">
                        <uib-pagination total-items="totalItens" ng-model="currentPage" max-size="maxSize" class="pagination-sm" boundary-links="true" force-ellipses="true" items-per-page="itensPerPage" num-pages="numPages"></uib-pagination>
                    </div>
                    <div class="item-paginacao">
                        <select class="form-control itens-por-pagina item-paginacao"  ng-model="itensPerPage">
                            <option ng-selected="true">10</option>
                            <option>25</option>
                            <option>50</option>
                            <option>100</option>
                        </select>
                    </div>
                    <div class="item-paginacao">
                        <div class="resumo-pagina">&nbsp; <% primeiroDaPagina %> - <% (ultimoDaPagina) %> de <% totalItens %></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Excluir-->
        <div class="modal fade" id="modalExcluir" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Excluir</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-3">
                                <img  ng-src="imagens/mensagens/xs-<% imagemExcluir %>" width="100">
                            </div>
                            <div class="col-md-9">
                                <p><% tituloExcluir %></p>
                            </div>
                        </div>
                        <div ng-show="processandoExcluir"><i class="fa fa-spinner fa-spin"></i> Processando...</div>
                        <div class="mensagem-ok text-center text-danger"><% mensagemExcluido %></div>
                    </div>
                    <div id="opcoesExcluir" class="modal-footer" ng-show="!excluido">
                        <button id="btnExcluir" type="button" class="btn btn-default" ng-click="excluir(idExcluir);">Sim</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Não</button>
                    </div>
                    <div id="fecharExcluir" class="modal-footer" ng-show="excluido">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Fim Modal Excluir-->
        <style>
            .mensagem-lida{
                font-weight: normal;
            }
            .mensagem-nao-lida{
                font-weight: bolder;
            }
        </style>
    </div>
@endsection