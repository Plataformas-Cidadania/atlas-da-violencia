@extends('cms::layouts.app')

@section('content')
    {!! Html::script('assets-cms/js/controllers/importSerieCtrl.js') !!}
    <div ng-controller="importSerieCtrl">
        <div class="box-padrao">
            <h1><a href="cms/series"><i class="fa fa-arrow-circle-left"></i></a>&nbsp;&nbsp;Importar Série - {{$serie->titulo}}</h1>

            <div >
                <span class="texto-obrigatorio">* campos obrigatórios</span><br><br>
                {!! Form::open(['name' =>'form']) !!}

                <span class="btn btn-primary btn-file" ng-show="!fileArquivo && !arquivoBD">
                    Escolher Arquivo Arquivo <input  type="file" ngf-select ng-model="fileArquivo" name="fileArquivo" accept=".xlsx,.xls" ngf-max-size="100MB" ngf-model-invalid="errorFile">
                </span>
                <button class="btn btn-danger" ng-click="limparArquivo()" ng-show="fileArquivo || arquivoBD" type="button">Remover Arquivo</button>
                <a href="arquivos/artigos/<% arquivoBD %>" target="_blank" ng-show="arquivoBD"><% arquivoBD %></a>
                <a ng-show="fileArquivo"><% fileArquivo.name %></a>
                <br><br>

                <?php
                $abrangencias = [
                    '1' => 'País',
                    '2' => 'Região',
                    '3' => 'UF',
                    '4' => 'Município',
                    '5' => 'Micro-Região',
                ];
                ?>
                {!! Form::label('abrangencia', 'Abrangência *') !!}<br>
                {!! Form::select('abrangencia',
                        $abrangencias,
                null, ['class'=>"form-control width-medio <% validar(serie.abrangencia) %>", 'ng-model'=>'serie.abrangencia', 'ng-required'=>'true', 'init-model'=>'serie.abrangencia', 'placeholder' => 'Selecione']) !!}<br>


                <input type="hidden" name="id" ng-model="id" ng-init="id='{{$serie->id}}'"/>
                <div class="row">
                    <div class="col-md-1 col-lg-1 col-xs-3">
                        <button class="btn btn-info" type="button" ng-click="importar(fileArquivo)" ng-disabled="form.$invalid && form.artigo.$dirty">Importar</button>
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