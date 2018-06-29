@extends('cms::layouts.app')

@section('content')
    {!! Html::script('assets-cms/js/controllers/importSerieCtrl.js') !!}
    <div ng-controller="importSerieCtrl">
        <div class="box-padrao">
            <h1><a href="cms/series"><i class="fa fa-arrow-circle-left"></i></a>&nbsp;&nbsp;Importar Várias Séries</h1>

            <div >
                <span class="texto-obrigatorio">* campos obrigatórios</span><br><br>
                {!! Form::open(['name' =>'form']) !!}

                <span class="btn btn-primary btn-file" ng-show="!fileArquivo && !arquivoBD">
                    Escolher Arquivo <input  type="file" ngf-select ng-model="fileArquivo" name="fileArquivo" accept=".xlsx,.xls,.csv" ngf-max-size="100MB" ngf-model-invalid="errorFile" ng-required="true">
                </span>
                <button class="btn btn-danger" ng-click="limparArquivo()" ng-show="fileArquivo || arquivoBD" type="button">Remover Arquivo</button>
                <a href="arquivos/artigos/<% arquivoBD %>" target="_blank" ng-show="arquivoBD"><% arquivoBD %></a>
                <a ng-show="fileArquivo"><% fileArquivo.name %></a>
                <br><br>


                <div style="display:none;">
                    <label for="modelo">Modelo de Arquivo</label>
                    <select class="form-control width-medio" name="modelo" id="modelo" ng-model="modelo" ng-required="true" ng-init="modelo='1'">
                        <option value="">Selecione</option>
                        <option value="1">Periodos em Linhas</option>
                        {{--<option value="2">Periodos em Colunas</option>--}}
                    </select>
                    <br><br>
                </div>

                <div class="row">
                    <div class="col-md-1 col-lg-1 col-xs-3">
                        <button class="btn btn-info" type="button" ng-click="importar(fileArquivo)" ng-disabled="form.$invalid">Importar</button>
                    </div>
                    <div class="col-md-3 col-lg-3 col-xs-6">
                        <span class="progress" ng-show="picFile.progress >= 0">
                            <div style="width: <% picFile.progress %>%" ng-bind="picFile.progress + '%'"></div>
                        </span>
                        <div ng-show="processandoSalvar"><i class="fa fa-spinner fa-spin"></i> Processando...</div>
                        <div>&nbsp;&nbsp;<% mensagemSalvar %></div>
                        <span ng-show="picFile.result">{{--Upload Successful--}}</span>
                        <span class="err" ng-show="errorMsg"><% errorMsg %></span>
                    </div>
                    <div class="col-md-9 col-xs-3"></div>
                </div>
                <br><br><br>

                <div id="modelo1">
                    <div><strong>Exemplo: series.csv</strong></div>
                    <div style="padding: 10px; background-color: #ccc; color:#333; width:400px;">
                        <div>serie;abrangencia;cod;nome;valor;periodo</div>
                        <div>1;3;RJ;Rio de Janeiro;21;2018</div>
                        <div>1;3;SP;São Paulo;11;2018</div>
                        <div>2;3;RJ;Rio de Janeiro;12;2018</div>
                        <div>2;3;SP;São Paulo;25;2018</div>
                    </div>
                    <br>
                </div>

                <div id="modelo2">

                </div>

                <div><strong>Abrangências</strong></div>
                <div style="padding: 10px; background-color: #ccc; color:#333; width:400px;">
                    <div>1 - País</div>
                    <div>2 - Região</div>
                    <div>3 - UF</div>
                    <div>4 - Municípios</div>
                </div>
                <br>

                {!! Form::close()!!}
            </div>
        </div>
    </div>
@endsection