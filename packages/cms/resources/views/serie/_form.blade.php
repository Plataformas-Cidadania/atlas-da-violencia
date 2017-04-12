{{--É NECESSÁRIO RODAR O COMANDO composer require illuminate/html E ALTERAR ACRESCENTAR LINHA NO ARQUIVO config/app.php--}}

{!! Form::label('idioma_sigla', 'Idioma *') !!}<br>
{!! Form::select('idioma_sigla',
        $idiomas,
null, ['class'=>"form-control width-medio <% validar(serie.idioma_sigla) %>", 'ng-model'=>'serie.idioma_sigla', 'ng-required'=>'true', 'init-model'=>'serie.idioma_sigla', 'placeholder' => 'Selecione']) !!}<br>

{!! Form::hidden('tema_id', 1, ['class'=>"form-control width-grande <% validar(serie.tema_id) %>", 'ng-model'=>'serie.tema_id', 'ng-required'=>'true', 'init-model'=>'serie.tema_id', 'placeholder' => '']) !!}<br>

{!! Form::label('titulo', 'Título *') !!}<br>
{!! Form::text('titulo', null, ['class'=>"form-control width-grande <% validar(serie.titulo) %>", 'ng-model'=>'serie.titulo', 'ng-required'=>'true', 'init-model'=>'serie.titulo', 'placeholder' => '']) !!}<br>

{!! Form::label('serie_id', 'Séries *') !!}<br>
{!! Form::select('serie_id',
        $series_relacionado,
null, ['class'=>"form-control width-medio <% validar(serie.serie_id) %>", 'ng-model'=>'serie.serie_id', 'init-model'=>'serie.serie_id', 'placeholder' => 'Principal']) !!}<br>


{!! Form::label('periodicidade_id', 'Periodicidade *') !!}<br>
{!! Form::select('periodicidade_id',
        $periodicidades,
null, ['class'=>"form-control width-medio <% validar(serie.periodicidade) %>", 'ng-model'=>'serie.periodicidade_id', 'ng-required'=>'true', 'init-model'=>'serie.periodicidade_id', 'placeholder' => 'Selecione']) !!}<br>

<?php 
    $indicadores = [
        '1' => 'Quantidade', 
        '2' => 'Taxa por 100 mil Habitantes'
    ];
?>

{!! Form::label('indicador', 'Indicadores *') !!}<br>
{!! Form::select('indicador',
        $indicadores,
null, ['class'=>"form-control width-medio <% validar(serie.indicador) %>", 'ng-model'=>'serie.indicador', 'ng-required'=>'true', 'init-model'=>'serie.indicador', 'placeholder' => 'Selecione']) !!}<br>

<?php 
    $unidades = [
        '1' => 'Quantidade', 
        '2' => 'Valor',
        '3' => 'Porcentagem',
    ];
?>

{!! Form::label('unidade', 'Unidades *') !!}<br>
{!! Form::select('unidade',
        $unidades,
null, ['class'=>"form-control width-medio <% validar(serie.unidade) %>", 'ng-model'=>'serie.unidade', 'ng-required'=>'true', 'init-model'=>'serie.unidade', 'placeholder' => 'Selecione']) !!}<br>

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

{!! Form::label('descricao', 'Metadados *') !!}<br>
{!! Form::textarea('descricao', null, ['class'=>"form-control width-grande <% validar(serie.descricao) %>", 'ui-tinymce'=>'tinymceOptions', 'ng-model'=>'serie.descricao', 'init-model'=>'serie.descricao']) !!}<br>



{!! Form::label('fonte_id', 'Fonte *') !!}<br>
{!! Form::select('fonte_id',
        $fontes,
null, ['class'=>"form-control width-medio <% validar(serie.fonte_id) %>", 'ng-model'=>'serie.fonte_id', 'ng-required'=>'true', 'init-model'=>'serie.fonte_id', 'placeholder' => 'Selecione']) !!}<br>


{{--<input type="hidden" name="serie_id" ng-model="serie.serie_id" ng-init="serie.serie_id=0"/>--}}




