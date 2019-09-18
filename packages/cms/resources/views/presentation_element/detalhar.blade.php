@extends('cms::layouts.app')

@section('content')
    {!! Html::script('assets-cms/js/controllers/alterarPresentationElementCtrl.js') !!}
    <div ng-controller="alterarPresentationElementCtrl">
        <div class="box-padrao">
            <h1><a href="javascript:history.back();"><i class="fa fa-arrow-circle-left"></i></a>&nbsp;&nbsp;Elemento de Apresentação</h1>
            <?php //print_r($element);?>
            <div ng-init="carregaImagem('{{$element->content}}', '{{$element->content}}')">
                <span class="texto-obrigatorio">* campos obrigatórios</span><br><br>
                {!! Form::model($element, ['name' =>'form']) !!}


                <br><br>
                @include('cms::presentation_element._form')
                <input type="hidden" name="id" ng-model="id" ng-init="id='{{$element->id}}'"/>
                <div class="row">
                    <div class="col-md-1 col-lg-1 col-xs-3">
                        <button class="btn btn-info" type="button" ng-click="alterar(picFile, fileArquivo)" ng-disabled="form.$invalid && form.item.$dirty">Salvar</button>
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