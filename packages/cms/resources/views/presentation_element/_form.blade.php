{{--É NECESSÁRIO RODAR O COMANDO composer require illuminate/html E ALTERAR ACRESCENTAR LINHA NO ARQUIVO config/app.php--}}
{!! Form::hidden('presentation_id', $presentation_id, ['ng-model'=>'element.presentation_id', 'ng-required'=>'true', 'init-model'=>'element.presentation_id', 'placeholder' => '']) !!}<br>

{!! Form::label('language', 'Idioma *') !!}<br>
{!! Form::select('language',
        $idiomas,
null, ['class'=>"form-control width-medio <% validar(element.language) %>", 'ng-model'=>'element.language', 'ng-required'=>'true', 'init-model'=>'element.language', 'placeholder' => 'Selecione']) !!}<br>

{!! Form::label('status', 'Status *') !!}<br>
{!! Form::select('status',
        array(
            '1' => 'Ativo',
            '0' => 'Inativo',
        ),
null, ['class'=>"form-control width-medio <% validar(element.status) %>", 'ng-model'=>'element.status', 'ng-required'=>'true', 'init-model'=>'element.status', 'placeholder' => 'Selecione']) !!}<br>


<?php
$qtdRows = 20;
$rows = [];
for($i=1;$i<=$qtdRows;$i++){
    $rows[$i] = $i;
}
?>
{!! Form::label('row', 'Linha *') !!}<br>
{!! Form::select('row',
        $rows,
null, ['class'=>"form-control width-medio <% validar(element.row) %>", 'ng-model'=>'element.row', 'ng-required'=>'true', 'init-model'=>'element.row', 'placeholder' => 'Selecione']) !!}<br>

{!! Form::label('position', 'Posição *') !!}<br>
{!! Form::select('position',
        array(
            'left' => 'Esquerda',
            'right' => 'Direita',
            'full' => 'Tudo',
        ),
null, ['class'=>"form-control width-medio <% validar(element.position) %>", 'ng-model'=>'element.position', 'ng-required'=>'true', 'init-model'=>'element.position', 'placeholder' => 'Selecione']) !!}<br>

{!! Form::label('type', 'Tipo *') !!}<br>
{!! Form::select('type',
        array(
            '1' => 'Texto',
            '2' => 'Gráfico',
            '3' => 'Imagem',
            '4' => 'HTML',
        ),
null, ['class'=>"form-control width-medio <% validar(element.type) %>", 'ng-model'=>'element.type', 'ng-required'=>'true', 'init-model'=>'element.type', 'placeholder' => 'Selecione']) !!}<br>

<div ng-if="element.type==1">
    {!! Form::label('content', 'Texto *') !!}<br>
    {!! Form::textarea('content', null, ['class'=>"form-control width-grande <% validar(element.content) %>", 'ui-tinymce'=>'tinymceOptions', 'ng-model'=>'element.content', 'init-model'=>'element.content']) !!}<br>
</div>
<div ng-if="element.type==2">
{!! Form::label('chart_type', 'Gráfico *') !!}<br>
{!! Form::select('chart_type',
        array(
            '1' => 'Barra',
            '2' => 'Linha',
            '3' => 'Barra Stacked',
            '4' => 'Linha Dashed',
            '5' => 'Área',
            '6' => 'Barra Porcentagem',
        ),
null, ['class'=>"form-control width-medio <% validar(element.chart_type) %>", 'ng-model'=>'element.chart_type', 'ng-required'=>'true', 'init-model'=>'element.chart_type', 'placeholder' => 'Selecione']) !!}<br>
</div>

<?php $rota = Route::getCurrentRoute()->getPath();?>
@if($rota=="cms/presentation-elements/{presentation_id}")

    <div ng-show="element.type==2">
        <span class="btn btn-primary btn-file" ng-show="!fileArquivo" >
            Escolher Arquivo CSV <input  type="file" ngf-select ng-model="fileArquivo" name="fileArquivo" accept=".csv" ngf-max-size="100MB" ngf-model-invalid="errorFile">
        </span>
        <a ng-show="fileArquivo"><% fileArquivo.name %></a>
        <br><br>
        <div>
            <div><strong>Exemplo: teste.csv</strong></div>
            <div style="padding: 10px; background-color: #ccc; color:#333; width:400px;">
                dataset;name;x;y<br>
                0;ccc;2013;10<br>
                0;ccc;2014;15<br>
                0;ccc;2015;18<br>
                0;ccc;2016;20<br>
                0;ccc;2017;17<br>
                0;ccc;2018;21<br>
                1;ddd;2013;15<br>
                1;ddd;2014;19<br>
                1;ddd;2015;16<br>
                1;ddd;2016;18<br>
                1;ddd;2017;22<br>
                1;ddd;2018;26<br>
            </div>
            <br>
        </div>
    </div>

    <div  ng-show="element.type==3">
        <div class="container-thumb">
            <div class="box-thumb" name="fileDrop" ngf-drag-over-class="'box-thumb-hover'" ngf-drop ngf-select ng-model="picFile"
                 ng-show="!picFile" accept="image/*" ngf-max-size="2MB">Solte uma imagem aqui!</div>
            <img  ngf-thumbnail="picFile" class="thumb">
        </div>
        <br>
        <span class="btn btn-primary btn-file" ng-show="!picFile">
                        Escolher Arquivo de Imagem <input  type="file" ngf-select ng-model="picFile" name="file" accept="image/*" ngf-max-size="2MB" ngf-model-invalid="errorFile">
                    </span>
        <button class="btn btn-danger" ng-click="picFile = null" ng-show="picFile" type="button">Remover Imagem</button>
        <i ng-show="form.file.$error.maxSize || form.fileDrop.$error.maxSize" style="margin-left: 10px;">
            Arquivo muito grande <% errorFile.size / 1000000|number:1 %>MB: máximo 2MB
            <div class="btn btn-danger" ng-click="limparImagem()">Cancelar</div>
        </i>
        <br><br>
    </div>

    <div ng-show="element.type==4">

        {!! Form::label('height', 'Altura (px)') !!} <span style="font-size: 11px; color: #6b6b6b;">Ex.: 800</span><br>
        {!! Form::text('height', null, ['class'=>"form-control width-medio <% validar(element.height) %>", 'ng-model'=>'element.height',  'init-model'=>'element.height', 'placeholder' => '']) !!}<br>

        <span class="btn btn-primary btn-file" ng-show="!fileArquivo" >
            Escolher Arquivo HTML <input  type="file" ngf-select ng-model="fileArquivo" name="fileArquivo" accept=".html,.htm" ngf-max-size="100MB" ngf-model-invalid="errorFile">
        </span>
        <a ng-show="fileArquivo"><% fileArquivo.name %></a>
        <br><br>
    </div>

@endif

@if($rota=="cms/presentation-element/{id}")

    <div ng-show="element.type==2">
        <span class="btn btn-primary btn-file" ng-show="!fileArquivo && !arquivoBD">
                    Escolher Arquivo CSV <input  type="file" ngf-select ng-model="fileArquivo" name="fileArquivo" accept=".csv" ngf-max-size="100MB" ngf-model-invalid="errorFile">
                </span>
        <button class="btn btn-danger" ng-click="limparArquivo()" ng-show="fileArquivo || arquivoBD" type="button">Remover Arquivo</button>
        <a href="arquivos/presentation-elements/<% arquivoBD %>" target="_blank" ng-show="arquivoBD"><% arquivoBD %></a>
        <a ng-show="fileArquivo"><% fileArquivo.name %></a>
        <br><br>
        <div>
            <div><strong>Exemplo: teste.csv</strong></div>
            <div style="padding: 10px; background-color: #ccc; color:#333; width:400px;">
                dataset;name;x;y<br>
                0;ccc;2013;10<br>
                0;ccc;2014;15<br>
                0;ccc;2015;18<br>
                0;ccc;2016;20<br>
                0;ccc;2017;17<br>
                0;ccc;2018;21<br>
                1;ddd;2013;15<br>
                1;ddd;2014;19<br>
                1;ddd;2015;16<br>
                1;ddd;2016;18<br>
                1;ddd;2017;22<br>
                1;ddd;2018;26<br>
            </div>
            <br>
        </div>
    </div>

    <div  ng-show="element.type==3">
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

    <div ng-show="element.type==4">

        {!! Form::label('height', 'Altura (px)') !!} <span style="font-size: 11px; color: #6b6b6b;">Ex.: 800</span><br>
        {!! Form::text('height', null, ['class'=>"form-control width-medio <% validar(element.height) %>", 'ng-model'=>'element.height',  'init-model'=>'element.height', 'placeholder' => '']) !!}<br>

        <span class="btn btn-primary btn-file" ng-show="!fileArquivo && !arquivoBD">
                    Escolher Arquivo HTML <input  type="file" ngf-select ng-model="fileArquivo" name="fileArquivo" accept=".html,.htm" ngf-max-size="100MB" ngf-model-invalid="errorFile">
                </span>
        <button class="btn btn-danger" ng-click="limparArquivo()" ng-show="fileArquivo || arquivoBD" type="button">Remover Arquivo</button>
        <a href="arquivos/presentation-elements/<% arquivoBD %>" target="_blank" ng-show="arquivoBD"><% arquivoBD %></a>
        <a ng-show="fileArquivo"><% fileArquivo.name %></a>
        <br><br>
    </div>


@endif
