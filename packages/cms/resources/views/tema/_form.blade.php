<?php $rota = Route::getCurrentRoute()->getPath();?>
@if($rota == 'cms/temas' || $rota == 'cms/temas/{tema_id}')
    {!! Form::label('idioma_sigla', 'Idioma *') !!}<br>
    {!! Form::select('idioma_sigla',
            $idiomas,
    null, ['class'=>"form-control width-medio <% validar(idioma.idioma_sigla) %>", 'ng-model'=>'idioma.idioma_sigla', 'ng-required'=>'true', 'init-model'=>'idioma.idioma_sigla', 'placeholder' => 'Selecione']) !!}<br>
@endif

{!! Form::label('tipo', 'Tipo *') !!}<br>
{!! Form::select('tipo',
        array(
            '0' => 'Todos',
            '1' => 'Séries',
            '2' => 'Consultas',
        ), null, ['class'=>"form-control width-medio <% validar(tema.tipo) %>", 'ng-model'=>'tema.tipo', 'ng-required'=>'true', 'init-model'=>'tema.tipo', 'placeholder' => '']) !!}<br>


{{--

{!! Form::label('tema_id', 'Temas *') !!}<br>
{!! Form::select('tema_id',
        $temas,
null, ['class'=>"form-control width-medio <% validar(tema.tema_id) %>", 'ng-model'=>'tema.tema_id', 'init-model'=>'tema.tema_id', 'placeholder' => 'Principal']) !!}<br>
--}}

{!! Form::hidden('tema_id', $tema_id, ['class'=>"form-control width-grande <% validar(tema.tema_id) %>", 'ng-model'=>'tema.tema_id', 'ng-required'=>'true', 'init-model'=>'tema.tema_id', 'placeholder' => '']) !!}

@if($rota == 'cms/temas' || $rota == 'cms/temas/{tema_id}')
{!! Form::label('titulo', 'Tema *') !!}<br>
{!! Form::text('titulo', null, ['class'=>"form-control width-grande <% validar(idioma.titulo) %>", 'ng-model'=>'idioma.titulo', 'ng-required'=>'true', 'init-model'=>'idioma.titulo', 'placeholder' => '']) !!}<br>

{!! Form::label('resumida', 'Descrição Resumida *') !!}<br>
{!! Form::text('resumida', null, ['class'=>"form-control width-grande <% validar(idioma.resumida) %>", 'ng-model'=>'idioma.resumida', 'ng-required'=>'true', 'init-model'=>'idioma.resumida', 'placeholder' => '']) !!}<br>


{!! Form::label('descricao', 'Descrição Completa *') !!}<br>
{!! Form::textarea('descricao', null, ['class'=>"form-control width-grande <% validar(idioma.descricao) %>", 'ui-tinymce'=>'tinymceOptions', 'ng-model'=>'idioma.descricao', 'init-model'=>'idioma.descricao']) !!}<br>

@endif

{!! Form::label('position', 'Posição *') !!}<br>
{!! Form::text('position', null, ['class'=>"form-control width-pequeno <% validar(tema.position) %>", 'ng-model'=>'tema.position', 'ng-required'=>'true', 'init-model'=>'tema.position', 'placeholder' => '']) !!}<br>