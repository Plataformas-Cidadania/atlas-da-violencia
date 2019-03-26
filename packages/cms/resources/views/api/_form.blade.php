<?php $rota = Route::getCurrentRoute()->getPath();?>


@if($rota=='cms/apis')
    {!! Form::label('idioma_sigla', 'Idioma *') !!}<br>
    {!! Form::select('idioma_sigla',
            $idiomas,
    null, ['class'=>"form-control width-medio <% validar(idioma.idioma_sigla) %>", 'ng-model'=>'idioma.idioma_sigla', 'ng-required'=>'true', 'init-model'=>'idioma.idioma_sigla', 'placeholder' => 'Selecione']) !!}<br>
@endif

<?php
$versaos = [
    '1' => 'v1',
];
?>

{!! Form::label('versao', 'Versaos de Valores *') !!}<br>
{!! Form::select('versao',
        $versaos,
null, ['class'=>"form-control width-medio <% validar(api.versao) %>", 'ng-model'=>'api.versao', 'ng-required'=>'true', 'init-model'=>'api.versao', 'placeholder' => 'Selecione']) !!}<br>

<?php
$tipos = [
    '1' => 'GET',
    '2' => 'POST',
];
?>

{!! Form::label('tipo', 'Tipos de Valores *') !!}<br>
{!! Form::select('tipo',
        $tipos,
null, ['class'=>"form-control width-medio <% validar(api.tipo) %>", 'ng-model'=>'api.tipo', 'ng-required'=>'true', 'init-model'=>'api.tipo', 'placeholder' => 'Selecione']) !!}<br>

{!! Form::label('url', 'Rota *') !!}<br>
{!! Form::select('url',
        array(
            'api/v1/fontes' => 'api/v1/fontes',
            'api/v1/fontes/{order}' => 'api/v1/fontes/{order}',
            'api/v1/fonte/{id}' => 'api/v1/fonte/{id}',
            'api/v1/unidades' => 'api/v1/unidades',
            'api/v1/unidade/{id}' => 'api/v1/unidade/{id}',
            'api/v1/periodicidades' => 'api/v1/periodicidades',
            'api/v1/periodicidade/{id}' => 'api/v1/periodicidade/{id}',
            'api/v1/indicadores' => 'api/v1/indicadores',
            'api/v1/indicador/{id}' => 'api/v1/indicador/{id}',
            'api/v1/temas' => 'api/v1/temas',
            'api/v1/tema/{id}' => 'api/v1/tema/{id}',
            'api/v1/valores-series/{serie_id}/{abrangencia}' => 'api/v1/valores-series/{serie_id}/{abrangencia}',
            'api/v1/valores-series/{serie_id}/{abrangencia}/{inical}/{final}' => 'api/v1/valores-series/{serie_id}/{abrangencia}/{inical}/{final}',
            'api/v1/valores-series-por-regioes/{serie_id}/{abrangencia}/{regioes}' => 'api/v1/valores-series-por-regioes/{serie_id}/{abrangencia}/{regioes}',
            'api/v1/valores-series-por-regioes/{serie_id}/{abrangencia}/{regioes}/{inical}/{final}' => 'api/v1/valores-series-por-regioes/{serie_id}/{abrangencia}/{regioes}/{inical}/{final}',
        ), null, ['class'=>"form-control width-medio <% validar(api.url) %>", 'ng-model'=>'api.url', 'ng-required'=>'true', 'init-model'=>'api.url', 'placeholder' => '', 'ng-change' => 'routeToTitle()']) !!}<br>



@if($rota=='cms/apis')
{!! Form::label('titulo', 'Título *') !!}<br>
{!! Form::text('titulo', null, ['class'=>"form-control width-grande <% validar(idioma.titulo) %>", 'ng-model'=>'idioma.titulo', 'ng-required'=>'true', 'init-model'=>'idioma.titulo', 'placeholder' => '']) !!}<br>

{!! Form::label('descricao', 'Parâmetros *') !!}<br>
{!! Form::textarea('descricao', null, ['class'=>"form-control width-grande <% validar(idioma.descricao) %>", 'ui-tinymce'=>'tinymceOptions', 'ng-model'=>'idioma.descricao', 'init-model'=>'idioma.descricao', 'placeholder' => '']) !!}<br>
@endif

{{--
{!! Form::label('url', 'Rota *') !!}<br>
{!! Form::text('url', null, ['class'=>"form-control width-grande <% validar(api.url) %>", 'ng-model'=>'api.url', 'ng-required'=>'true', 'init-model'=>'api.url', 'placeholder' => '']) !!}<br>
--}}




{!! Form::label('resposta', 'Resposta *') !!}<br>
{!! Form::textarea('resposta', null, ['class'=>"form-control width-grande <% validar(api.resposta) %>", 'ui-tinymce'=>'tinymceOptions', 'ng-model'=>'api.resposta', 'ng-required'=>'true', 'init-model'=>'api.resposta', 'placeholder' => '']) !!}<br>

