<?php $rota = Route::getCurrentRoute()->getPath();?>

@if(empty($rota!='cms/radares'))
    {!! Form::label('idioma_sigla', 'Idioma *') !!}<br>
    {!! Form::select('idioma_sigla',
            $idiomas,
    null, ['class'=>"form-control width-medio <% validar(idioma.idioma_sigla) %>", 'ng-model'=>'idioma.idioma_sigla', 'ng-required'=>'true', 'init-model'=>'idioma.idioma_sigla', 'placeholder' => 'Selecione']) !!}<br>

    {!! Form::label('titulo', 'Título *') !!}<br>
    {!! Form::text('titulo', null, ['class'=>"form-control width-grande <% validar(idioma.titulo) %>", 'ng-model'=>'idioma.titulo', 'ng-required'=>'true', 'init-model'=>'idioma.titulo', 'placeholder' => '']) !!}<br>
@endif

{!! Form::label('tipo', 'Tipo *') !!}<br>
{!! Form::select('tipo',
        array(
            '1' => 'Rodoviário',
            '2' => 'Ferroviário',
            '3' => 'Aéreo',
            '4' => 'Aquaviário',
            '5' => 'Outros',
        ), null, ['class'=>"form-control width-medio <% validar(radar.tipo) %>", 'ng-model'=>'radar.tipo', /*'ng-required'=>'true',*/ 'init-model'=>'radar.tipo', 'placeholder' => 'Selecione']) !!}<br>

{!! Form::label('slug', 'Slug *') !!}<br>
{!! Form::text('slug', null, ['class'=>"form-control width-grande <% validar(radar.slug) %>", 'ng-model'=>'radar.slug', 'ng-required'=>'true', 'init-model'=>'radar.slug', 'placeholder' => '']) !!}<br>

