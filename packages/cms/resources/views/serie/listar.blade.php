@extends('cms::layouts.app')

@section('content')
    {!! Html::script('/assets-cms/js/controllers/serieCtrl.js') !!}
<script>
    $(function () {
        $('[data-toggle="popover"]').popover()
    })
</script>
    <div ng-controller="serieCtrl">
        <div class="box-padrao">
            <h1><i class="fa fa-fw fa-cubes"></i>&nbsp;Series</h1>
            <button class="btn btn-primary" ng-click="mostrarForm=!mostrarForm" ng-show="!mostrarForm">Nova Serie</button>
            <button class="btn btn-warning" ng-click="mostrarForm=!mostrarForm" ng-show="mostrarForm">Cancelar</button>
            <a class="btn btn-success" href="cms/importar-varias-series" style="float: right;"><i class="fa fa-upload " title="Importar"></i> Importar Dados</a>
            <br><br>
            <div ng-show="mostrarForm">
                <span class="texto-obrigatorio" ng-show="form.$invalid">* campos obrigatórios</span><br><br>
                {!! Form::open(['name' =>'form']) !!}
                <div style="display:none;">
                    <div class="container-thumb">
                        <div class="box-thumb" name="fileDrop" ngf-drag-over-class="'box-thumb-hover'" ngf-drop ngf-select ng-model="picFile"
                             ng-show="!picFile" accept="image/*" ngf-max-size="2MB">Solte uma imagem aqui!</div>
                        <img  ngf-thumbnail="picFile" class="thumb">
                    </div>
                    <br>
                    <span class="btn btn-primary btn-file" ng-show="!picFile">
                    Escolher imagem <input  type="file" ngf-select ng-model="picFile" name="file" accept="image/*" ngf-max-size="2MB" ngf-model-invalid="errorFile">
                </span>
                    <button class="btn btn-danger" ng-click="picFile = null" ng-show="picFile" type="button">Remover Imagem</button>
                    <i ng-show="form.file.$error.maxSize || form.fileDrop.$error.maxSize" style="margin-left: 10px;">
                        Arquivo muito grande <% errorFile.size / 1000000|number:1 %>MB: máximo 2MB
                        <div class="btn btn-danger" ng-click="limparImagem()">Cancelar</div>
                    </i>

                    <br><br>
                </div>
                <div ng-show="tipo_arquivo">
                    <span class="btn btn-primary btn-file" ng-show="!fileArquivo">
                    Escolher Arquivo <input  type="file" ngf-select ng-model="fileArquivo" name="fileArquivo" accept="application/pdf,.html,.htm" ngf-max-size="100MB" ngf-model-invalid="errorFile">
                </span>
                    <a ng-show="fileArquivo"><% fileArquivo.name %></a>
                    <br><br>
                </div>

                <span class="btn btn-primary btn-file" ng-show="!fileArquivoMetadados">
                    Escolher Arquivo Metadados <input  type="file" ngf-select ng-model="fileArquivoMetadados" name="fileArquivoMetadados" accept="application/pdf,.txt,.doc,.docx" ngf-max-size="10MB" ngf-model-invalid="errorFile">
                </span>
                <a ng-show="fileArquivoMetadados"><% fileArquivoMetadados.name %></a>
                <br><br>

                @include('cms::serie._form')
                <div class="row">
                    <div class="col-md-1 col-lg-1 col-xs-3">
                        <button class="btn btn-info" type="button" ng-click="inserir(picFile, fileArquivo, fileArquivoMetadados)" ng-disabled="form.$invalid">Salvar</button>
                    </div>
                    <div class="col-md-2 col-lg-2 col-xs-6">
                        <span class="progress" ng-show="picFile.progress >= 0">
                            <div style="width: <% picFile.progress %>%" ng-bind="picFile.progress + '%'"></div>
                        </span>
                        <div ng-show="processandoInserir"><i class="fa fa-spinner fa-spin"></i> Processando...</div>
                        <div><% mensagemInserir %></div>
                        <span ng-show="picFile.result">{{--Upload Successful--}}</span>
                        <span class="err" ng-show="errorMsg"><% errorMsg %></span>
                    </div>
                    <div class="col-md-9 col-xs-3"></div>
                </div>

                <br><br><br>





                {!! Form::close()!!}
            </div>
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
                    <div><% mensagemSerier %></div>
                    <div ng-show="processandoListagem"><i class="fa fa-spinner fa-spin"></i> Processando...</div>
                    <h2 class="tabela_vazia" ng-show="!processandoListagem && totalItens==0">Nenhum registro encontrado!</h2>
                    <table ng-show="totalItens>0" class="table table-striped">
                        <thead>
                        <tr>
                            <th ng-click="ordernarPor('id')" style="serier:pointer;">
                                Id
                                <i ng-if="ordem=='id' && sentidoOrdem=='asc'" class="fa fa-angle-double-down"></i>
                                <i ng-if="ordem=='id' && sentidoOrdem=='desc'" class="fa fa-angle-double-up"></i>
                            </th>
                            <th style="display:none;">Imagem</th>
                            <th ng-click="ordernarPor('serie')" style="serier:pointer;">
                                Serie
                                <i ng-if="ordem=='serie' && sentidoOrdem=='asc'" class="fa fa-angle-double-down"></i>
                                <i ng-if="ordem=='serie' && sentidoOrdem=='desc'" class="fa fa-angle-double-up"></i>
                            </th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr ng-repeat="serie in series">
                            <td><% serie.id %></td>
                            <td style="display:none;"><img ng-show="serie.imagem" ng-src="imagens/series/xs-<% serie.imagem %>" width="60"></td>
                            <td><a href="cms/serie/<% serie.id %>"><% serie.titulo %></a></td>
                            <td class="text-right">
                                <div>
                                    {{--<a href="cms/importar-serie/<% serie.id %>"><i class="fa fa-upload fa-2x" title="Importar"></i></a>--}}&nbsp;&nbsp;
                                    <a ng-class="<% serie.status %> == 1 ? 'color-success' : 'color-success-inactive'"  style="cursor: pointer;"><i class="fa fa-check-circle fa-2x" aria-hidden="true" ng-click="status(serie.id);"></i></a>
                                    <a href="cms/filtros-series/<% serie.id %>"><i class="fa fa-filter fa-2x" title="Filtros Séries"></i></a>&nbsp;&nbsp;
                                    <a href="cms/downloads/1/<% serie.id %>"><i class="fa fa-file fa-2x" title="Arquivos para Download"></i></a>&nbsp;&nbsp;
                                    <a href="cms/textos-series/<% serie.id %>"><i class="fa fa-language fa-2x" title="Idiomas"></i></a>&nbsp;&nbsp;
                                    <a href="cms/temas-series/<% serie.id %>"><i class="fa fa-folder-open fa-2x" title="Temas"></i></a>&nbsp;&nbsp;
                                    <a href="cms/serie/<% serie.id %>"><i class="fa fa-edit fa-2x" title="Editar"></i></a>&nbsp;&nbsp;
                                    <a><i data-toggle="modal" data-target="#modalExcluir" class="fa fa-remove fa-2x" ng-click="perguntaExcluir(serie.id, serie.titulo, serie.imagem)"></i></a>
                                    <a style="diplay:block;" title="excluir valores séries"><i data-toggle="modal" data-target="#modalLimparValores" class="fa fa-eraser fa-2x" ng-click="perguntaLimparValores(serie.id, serie.titulo, serie.imagem)"></i></a>
                                </div>
                            </td>
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
                                <img  ng-src="imagens/series/xs-<% imagemExcluir %>" width="100">
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



        <!-- Modal Limpar Valores Séries-->
        <div class="modal fade" id="modalLimparValores" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                    <form name="form2">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Limpar Valores.</h4>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <h4><strong><% idLimparValores %> - <% tituloLimparValores %></strong></h4>
                                </div>

                                <div class="col-md-12">
                                    {!! Form::label('tipoDados', 'Tipo de Dados *') !!}<br>
                                    {!! Form::select('tipoDados',
                                            [
                                                0 => 'Territórios',
                                                1 => 'Pontos',
                                            ],
                                    null, ['class'=>"form-control width-medio <% validar(tipoDados) %>", 'ng-model'=>'tipoDados', 'ng-required'=>'true', 'init-model'=>'tipoDados']) !!}<br>
                                </div>


                                <div class="col-md-12" ng-show="tipoDados==0">
                                    <p>
                                        <?php
                                        $abrangencias = [
                                            '0' => 'Todas',
                                            /*'1' => 'País',
                                            '2' => 'Região',
                                            '3' => 'UF',
                                            '4' => 'Município',*/
                                        ];
                                        foreach ($optionsAbrangencias as $abrangencia){
                                            $abrangencias[$abrangencia->id] = $abrangencia->title;
                                        }
                                        ?>
                                        {!! Form::label('abrangencia', 'Abrangência') !!}<br>
                                        {!! Form::select('abrangencia',
                                                $abrangencias,
                                        null, [
                                            'class'=>"form-control width-medio",
                                            'ng-model'=>'abrangenciaLimpar',
                                            'ng-required'=>'true',
                                            'placeholder' => 'Selecione'
                                        ]) !!}<br>
                                    </p>
                                </div>

                                <div class="col-md-12" ng-show="tipoDados==1">

                                    <label for="ano_pontos">Ano</label>
                                    <input class="form-control" type="text" id="ano_pontos" name="ano_pontos" ng-model="ano_pontos"> 0 para todos
                                </div>

                            </div>
                            <div ng-show="processandoLimparValores"><i class="fa fa-spinner fa-spin"></i> Processando...</div>
                            <div class="mensagem-ok text-center text-danger"><% mensagemExcluidoValores %></div>
                        </div>
                        <div id="opcoesLimpar" class="modal-footer">
                            <button id="btnLimpar" type="button" class="btn btn-danger" ng-click="limpar(idLimparValores);" ng-disabled="form2.$invalid">Limpar</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Fim Modal Limpar Valores Séries-->
    </div>
@endsection
