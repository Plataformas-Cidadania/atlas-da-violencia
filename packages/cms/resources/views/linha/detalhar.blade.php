@extends('cms::layouts.app')

@section('content')
    {!! Html::script('assets-cms/js/controllers/alterarLinhaCtrl.js') !!}
    <div ng-controller="alterarLinhaCtrl">
        <div class="box-padrao">
            <h1><a href="cms/linhas"><i class="fa fa-arrow-circle-left"></i></a>&nbsp;&nbsp;Linhas</h1>
            <?php //print_r($linha);?>
            <div ng-init="carregaImagem('{{$linha->imagem}}', '{{$linha->icone}}')">
                <span class="texto-obrigatorio">* campos obrigatórios</span><br><br>
                {!! Form::model($linha, ['name' =>'form']) !!}

                <div class="row">
                    <div class="col-md-2">
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
                    <div class="col-md-2">
                        <div class="container-thumb">
                            <div class="box-thumb" name="fileDrop" ngf-drag-over-class="'box-thumb-hover'" ngf-drop ngf-select ng-model="picFileIcone"
                                 ng-show="!picFileIcone && !iconeBD" accept="image/*" ngf-max-size="2MB">Solte uma icone aqui!</div>
                            <img ng-show="picFileIcone" ngf-thumbnail="picFileIcone" class="thumb">
                            <img ng-show="iconeBD" class="thumb" ng-src="<% iconeBD %>">
                        </div>
                        <br>
                        <span class="btn btn-primary btn-file" ng-show="!picFileIcone && !iconeBD">
                        Escolher icone <input  type="file" ngf-select ng-model="picFileIcone" name="fileIcone" accept="image/*" ngf-max-size="2MB" ngf-model-invalid="errorFile">
                        </span>
                        <button class="btn btn-danger" ng-click="limparIcone()" ng-show="picFileIcone || iconeBD" type="button">Remover Icone</button>
                        <i ng-show="form.file.$error.maxSize" style="margin-left: 10px;">Arquivo muito grande <% errorFile.size / 1000000|number:1 %>MB: máximo 2MB</i>

                        <br><br>
                    </div>
                </div>

                @include('cms::linha._form')
                <input type="hidden" name="id" ng-model="id" ng-init="id='{{$linha->id}}'"/>
                <div class="row">
                    <div class="col-md-1 col-lg-1 col-xs-3">
                        <button class="btn btn-info" type="button" ng-click="alterar(picFile, picFileIcone)" ng-disabled="form.$invalid && form.linha.$dirty">Salvar</button>
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
