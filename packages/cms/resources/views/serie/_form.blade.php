{{--É NECESSÁRIO RODAR O COMANDO composer require illuminate/html E ALTERAR ACRESCENTAR LINHA NO ARQUIVO config/app.php--}}
@if(empty($serie))
{!! Form::label('idioma_sigla', 'Idioma *') !!}<br>
{!! Form::select('idioma_sigla',
        $idiomas,
null, ['class'=>"form-control width-medio <% validar(textos.idioma_sigla) %>", 'ng-model'=>'textos.idioma_sigla', 'ng-required'=>'true', 'init-model'=>'textos.idioma_sigla', 'placeholder' => 'Selecione']) !!}<br>
@endif
{{--

{!! Form::hidden('tema_id', 1, ['class'=>"form-control width-grande <% validar(serie.tema_id) %>", 'ng-model'=>'serie.tema_id', 'ng-required'=>'true', 'init-model'=>'serie.tema_id', 'placeholder' => '']) !!}<br>
--}}
@if(!empty($serie))
<h3>{{$serie->titulo}}</h3><br>
@endif
@if(empty($serie))
{!! Form::label('titulo', 'Título *') !!}<br>
{!! Form::text('titulo', null, ['class'=>"form-control width-grande <% validar(textos.titulo) %>", 'ng-model'=>'textos.titulo', 'ng-required'=>'true', 'init-model'=>'textos.titulo', 'placeholder' => '']) !!}<br>
@endif
{{--
{!! Form::label('serie_id', 'Séries *') !!}<br>
{!! Form::select('serie_id',
        $series_relacionado,
null, ['class'=>"form-control width-medio <% validar(serie.serie_id) %>", 'ng-model'=>'serie.serie_id', 'init-model'=>'serie.serie_id', 'placeholder' => 'Principal']) !!}<br>
--}}
{{--@foreach($periodicidades as $periodicidade)
    {{$periodicidade->titulo}}
@endforeach--}}

{{--OS CHECKBOX DE TIPOS SÃO USADOS PARA DEFINIR A COMBINAÇÃO DE TIPOS DO SELECT *TIPO_DADOS* DE FORMA AUTOMÁTICA--}}
<div class="checkbox-inline">
    {!! Form::checkbox('tipo_territorios', true, null, ['class'=>"checkbox-inline width-grande", 'ng-model'=>"tipo_territorios", 'init-model'=>"tipo_territorios", 'style'=>"width: 30px; height: 30px;"]) !!}
    {!! Form::label('territorios', $tipo_territorios, ['style'=>"padding: 8px 20px 0 20px;"]) !!}
    {!! Form::checkbox('tipo_pontos', true, null, ['class'=>"checkbox-inline width-grande", 'ng-model'=>"tipo_pontos", 'init-model'=>"tipo_pontos", 'style'=>"width: 30px; height: 30px;"]) !!}
    {!! Form::label('tipo_pontos', $tipo_territorios, ['style'=>"padding: 8px 20px 0 20px;"]) !!}
    {!! Form::checkbox('tipo_arquivo', true, null, ['class'=>"checkbox-inline width-grande", 'ng-model'=>"tipo_arquivo", 'init-model'=>"tipo_arquivo", 'style'=>"width: 30px; height: 30px;"]) !!}
    {!! Form::label('tipo_arquivo', $tipo_territorios, ['style'=>"padding: 8px 20px 0 20px;"]) !!}
</div>

{!! Form::label('tipo_dados', 'Tipo de Dados *') !!}<br>
{!! Form::select('tipo_dados',
        $tipos_dados_series,
null, ['class'=>"form-control width-medio <% validar(serie.tipo_dados) %>", 'ng-model'=>'serie.tipo_dados', 'ng-required'=>'true', 'init-model'=>'serie.tipo_dados', 'placeholder' => 'Selecione']) !!}<br>



{!! Form::label('periodicidade_id', 'Periodicidade *') !!}<br>
{!! Form::select('periodicidade_id',
$periodicidades,
null, ['class'=>"form-control width-medio <% validar(serie.periodicidade) %>", 'ng-model'=>'serie.periodicidade_id', 'ng-required'=>'true', 'init-model'=>'serie.periodicidade_id', 'placeholder' => 'Selecione']) !!}<br>

<?php 
    /*$indicadores = [
        '1' => 'Quantidade', 
        '2' => 'Taxa por 100 mil Habitantes',
        '3' => 'Proporção',
        '4' => 'Taxa Bayesiana'
    ];*/
?>

{!! Form::label('indicador', 'Indicadores *') !!}<br>
{!! Form::select('indicador',
        $indicadores,
null, ['class'=>"form-control width-medio <% validar(serie.indicador) %>", 'ng-model'=>'serie.indicador', 'ng-required'=>'true', 'init-model'=>'serie.indicador', 'placeholder' => 'Selecione']) !!}<br>

<?php 
    /*$unidades = [
        '1' => 'Quantidade', 
        '2' => 'Valor',
        '3' => 'Porcentagem',
    ];*/
?>

{!! Form::label('unidade', 'Unidades *') !!}<br>
{!! Form::select('unidade',
        $unidades,
null, ['class'=>"form-control width-medio <% validar(serie.unidade) %>", 'ng-model'=>'serie.unidade', 'ng-required'=>'true', 'init-model'=>'serie.unidade', 'placeholder' => 'Selecione']) !!}<br>

<?php
/*$abrangencias = [
        '1' => 'País', 
        '2' => 'Região',
        '3' => 'UF',
        '4' => 'Município',
        '5' => 'Micro-Região',
    ];*/
?>

{{--
{!! Form::label('abrangencia', 'Abrangência *') !!}<br>
{!! Form::select('abrangencia',
        $abrangencias,
null, ['class'=>"form-control width-medio <% validar(serie.abrangencia) %>", 'ng-model'=>'serie.abrangencia', 'ng-required'=>'true', 'init-model'=>'serie.abrangencia', 'placeholder' => 'Selecione']) !!}<br>
--}}

@if(empty($serie))
{!! Form::label('descricao', 'Metadados *') !!}<br>
{!! Form::textarea('descricao', null, ['class'=>"form-control width-grande <% validar(textos.descricao) %>", 'ui-tinymce'=>'tinymceOptions', 'ng-model'=>'textos.descricao', 'init-model'=>'textos.descricao']) !!}<br>
@endif


{!! Form::label('fonte_id', 'Fonte *') !!}<br>
{!! Form::select('fonte_id',
        $fontes,
null, ['class'=>"form-control width-medio <% validar(serie.fonte_id) %>", 'ng-model'=>'serie.fonte_id', 'ng-required'=>'true', 'init-model'=>'serie.fonte_id', 'placeholder' => 'Selecione']) !!}<br>


{{--<input type="hidden" name="serie_id" ng-model="serie.serie_id" ng-init="serie.serie_id=0"/>--}}




