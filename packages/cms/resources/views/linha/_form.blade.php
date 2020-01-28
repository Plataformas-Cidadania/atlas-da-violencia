<?php $rota = Route::getCurrentRoute()->getPath();?>
@if(!empty($transporte_id))
{!! Form::hidden('transporte_id', $transporte_id, ['ng-model'=>'linha.transporte_id', 'ng-required'=>'true', 'init-model'=>'linha.transporte_id', 'placeholder' => '']) !!}<br>
@endif
@if(empty($rota!='cms/linhas') || empty($rota!='cms/linhas/{transporte_id}'))
    {!! Form::label('idioma_sigla', 'Idioma *') !!}<br>
    {!! Form::select('idioma_sigla',
            $idiomas,
    null, ['class'=>"form-control width-medio <% validar(idioma.idioma_sigla) %>", 'ng-model'=>'idioma.idioma_sigla', 'ng-required'=>'true', 'init-model'=>'idioma.idioma_sigla', 'placeholder' => 'Selecione']) !!}<br>

    {!! Form::label('titulo', 'Título *') !!}<br>
    {!! Form::text('titulo', null, ['class'=>"form-control width-grande <% validar(idioma.titulo) %>", 'ng-model'=>'idioma.titulo', 'ng-required'=>'true', 'init-model'=>'idioma.titulo', 'placeholder' => '']) !!}<br>
@endif

{!! Form::label('slug', 'Slug *') !!}<br>
{!! Form::text('slug', null, ['class'=>"form-control width-grande <% validar(linha.slug) %>", 'ng-model'=>'linha.slug', 'ng-required'=>'true', 'init-model'=>'linha.slug', 'placeholder' => '']) !!}<br>

