@extends('cms::layouts.app')

@section('content')
    {!! Html::script('assets-cms/js/controllers/alterarSerieCtrl.js') !!}
    <div ng-controller="alterarSerieCtrl">
        <div class="box-padrao">
            <h1><a href="cms/series"><i class="fa fa-arrow-circle-left"></i></a>&nbsp;&nbsp;Series</h1>
            <?php //print_r($serie);?>
            <div ng-init="carregaImagem('{{$serie->imagem}}', '{{$serie->arquivo}}', '{{$serie->arquivo_metadados}}')">
                <span class="texto-obrigatorio">* campos obrigatórios</span><br><br>
                {!! Form::model($serie, ['name' =>'form']) !!}
                <div style="display:none;">
                    <div class="container-thumb">
                        <div class="box-thumb" name="fileDrop" ngf-drag-over-class="'box-thumb-hover'" ngf-drop ngf-select ng-model="picFile"
                             ng-show="!picFile && !imagemBD" accept="image/*" ngf-max-size="2MB">Solte uma imagem aqui!</div>
                        <img ng-show="picFile" ngf-thumbnail="picFile" class="thumb">
                        <img ng-show="imagemBD" class="thumb" ng-src="<% imagemBD %>">
                    </div>
                    <br>
                    <span class="btn btn-primary btn-file" ng-show="!picFile && !imagemBD">
                    Escolher imagem <input  type="file" ngf-select ng-model="picFile" name="file" accept="image/*" ngf-max-size="2MB" ngf-model-invalid="errorFile">
                </span>
                    <button class="btn btn-danger" ng-click="limparImagem()" ng-show="picFile || imagemBD" type="button">Remover Imagem</button>
                    <i ng-show="form.file.$error.maxSize" style="margin-left: 10px;">Arquivo muito grande <% errorFile.size / 1000000|number:1 %>MB: máximo 2MB</i>

                    <br><br>
                </div>
                <div ng-show="tipo_arquivo">
                    <span class="btn btn-primary btn-file" ng-show="!fileArquivo && !arquivoBD">
                    Escolher Arquivo <input  type="file" ngf-select ng-model="fileArquivo" name="fileArquivo" accept="application/pdf,.html,.htm" ngf-max-size="100MB" ngf-model-invalid="errorFile">
                    </span>
                    <button class="btn btn-danger" ng-click="limparArquivo()" ng-show="fileArquivo || arquivoBD" type="button">Remover Arquivo</button>
                    <a href="arquivos/series/<% arquivoBD %>" target="_blank" ng-show="arquivoBD"><% arquivoBD %></a>
                    <a ng-show="fileArquivo"><% fileArquivo.name %></a>
                    <br><br>
                </div>
                <span class="btn btn-primary btn-file" ng-show="!fileArquivoMetadados && !arquivoMetadadosBD">
                    Escolher Arquivo Metadados<input  type="file" ngf-select ng-model="fileArquivoMetadados" name="fileArquivoMetadados" accept="application/pdf,.txt,.doc,.docx" ngf-max-size="100MB" ngf-model-invalid="errorFile">
                    </span>
                <button class="btn btn-danger" ng-click="limparArquivoMetadados()" ng-show="fileArquivoMetadados || arquivoMetadadosBD" type="button">Remover Arquivo Metadados</button>
                <a href="arquivos/metadados/<% arquivoMetadadosBD %>" target="_blank" ng-show="arquivoMetadadosBD"><% arquivoMetadadosBD %></a>
                <a ng-show="fileArquivoMetadados"><% fileArquivoMetadados.name %></a>
                <br><br>


                @include('cms::serie._form')
                <input type="hidden" name="id" ng-model="id" ng-init="id='{{$serie->id}}'"/>
                <div class="row">
                    <div class="col-md-1 col-lg-1 col-xs-3">
                        <button class="btn btn-info" type="button" ng-click="alterar(picFile, fileArquivo, fileArquivoMetadados)" ng-disabled="form.$invalid && form.serie.$dirty">Salvar</button>
                    </div>
                    <div class="col-md-2 col-lg-2 col-xs-6">
                        <span class="progress" ng-show="picFile.progress >= 0">
                            <div style="width: <% picFile.progress %>%" ng-bind="picFile.progress + '%'"></div>
                        </span>
                        <div ng-show="processandoSalvar"><i class="fa fa-spinner fa-spin"></i> Processando...</div>
                        <div><% mensagemSalvar %></div>
                        <span ng-show="picFile.result">{{--Upload Successful--}}</span>
                        <span class="err" ng-show="errorMsg"><% errorMsg %></span>
                    </div>
                    <div class="col-md-9 col-xs-3"></div>
                </div>
                <br><br><br>




                {!! Form::close()!!}
            </div>
        </div>
    </div>
@endsection
