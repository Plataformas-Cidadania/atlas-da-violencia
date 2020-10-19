<?php $rota = Route::getCurrentRoute()->getPath();?>
@if($rota == 'cms/assuntos')
    {!! Form::label('idioma_sigla', 'Idioma *') !!}<br>
    {!! Form::select('idioma_sigla',
            $idiomas,
    null, ['class'=>"form-control width-medio <% validar(idioma.idioma_sigla) %>", 'ng-model'=>'idioma.idioma_sigla', 'ng-required'=>'true', 'init-model'=>'idioma.idioma_sigla', 'placeholder' => 'Selecione']) !!}<br>
@endif


@if($rota == 'cms/assuntos')
    {!! Form::label('titulo', 'Assunto *') !!}<br>
    {!! Form::text('titulo', null, ['class'=>"form-control width-grande <% validar(idioma.titulo) %>", 'ng-model'=>'idioma.titulo', 'ng-required'=>'true', 'init-model'=>'idioma.titulo', 'placeholder' => '']) !!}<br>
@endif

@if($rota == 'cms/assuntos')
    {!! Form::hidden('status', 1, ['class'=>"form-control width-grande <% validar(assunto.status) %>", 'ng-model'=>'assunto.status', 'ng-required'=>'true', 'init-model'=>'assunto.status', 'placeholder' => '']) !!}<br>
@endif