<?php $rota = Route::getCurrentRoute()->getPath();?>
{{--@if(empty($tema))
    {!! Form::label('idioma_sigla', 'Idioma *') !!}<br>
    {!! Form::select('idioma_sigla',
            $idiomas,
    null, ['class'=>"form-control width-medio <% validar(idioma.idioma_sigla) %>", 'ng-model'=>'idioma.idioma_sigla', 'ng-required'=>'true', 'init-model'=>'idioma.idioma_sigla', 'placeholder' => 'Selecione']) !!}<br>
@endif--}}


{!! Form::label('id', 'ID *') !!}<br>
{!! Form::text('id', null, ['class'=>"form-control width-grande <% validar(optionAbrangencia.id) %>", 'ng-model'=>'optionAbrangencia.id', 'ng-required'=>'true', 'init-model'=>'optionAbrangencia.id', 'placeholder' => '']) !!}<br>

@if($rota!='cms/option-abrangencia/{id}')
{!! Form::label('title', 'Título *') !!}<br>
{!! Form::text('title', null, ['class'=>"form-control width-grande <% validar(idioma.title) %>", 'ng-model'=>'idioma.title', 'ng-required'=>'true', 'init-model'=>'idioma.title', 'placeholder' => '']) !!}<br>

{!! Form::label('plural', 'Plural *') !!}<br>
{!! Form::text('plural', null, ['class'=>"form-control width-grande <% validar(idioma.plural) %>", 'ng-model'=>'idioma.plural', 'ng-required'=>'true', 'init-model'=>'idioma.plural', 'placeholder' => '']) !!}<br>
@endif
{!! Form::label('listAll', 'List All *') !!}<br>
{!! Form::text('listAll', null, ['class'=>"form-control width-grande <% validar(optionAbrangencia.listAll) %>", 'ng-model'=>'optionAbrangencia.listAll', 'ng-required'=>'true', 'init-model'=>'optionAbrangencia.listAll', 'placeholder' => '']) !!}<br>

{!! Form::label('on', 'On *') !!}<br>
{!! Form::text('on', null, ['class'=>"form-control width-grande <% validar(optionAbrangencia.on) %>", 'ng-model'=>'optionAbrangencia.on', 'ng-required'=>'true', 'init-model'=>'optionAbrangencia.on', 'placeholder' => '']) !!}<br>

{!! Form::label('height', 'Height *') !!}<br>
{!! Form::text('height', null, ['class'=>"form-control width-grande <% validar(optionAbrangencia.height) %>", 'ng-model'=>'optionAbrangencia.height', 'ng-required'=>'true', 'init-model'=>'optionAbrangencia.height', 'placeholder' => '']) !!}<br>

