{!! Form::hidden('option_abrangencia_id', $option_abrangencia_id, ['class'=>"form-control width-grande <% validar(optionAbrangencia.option_abrangencia_id) %>", 'ng-model'=>'optionAbrangencia.option_abrangencia_id', 'ng-required'=>'true', 'init-model'=>'optionAbrangencia.option_abrangencia_id', 'placeholder' => '']) !!}<br>

{!! Form::label('idioma_sigla', 'Idioma *') !!}<br>
{!! Form::select('idioma_sigla',
        $idiomas,
null, ['class'=>"form-control width-medio <% validar(optionAbrangencia.idioma_sigla) %>", 'ng-model'=>'optionAbrangencia.idioma_sigla', 'ng-required'=>'true', 'init-model'=>'optionAbrangencia.idioma_sigla', 'placeholder' => 'Selecione']) !!}<br>

{!! Form::label('title', 'Título *') !!}<br>
{!! Form::text('title', null, ['class'=>"form-control width-grande <% validar(optionAbrangencia.title) %>", 'ng-model'=>'optionAbrangencia.title', 'ng-required'=>'true', 'init-model'=>'optionAbrangencia.title', 'placeholder' => '']) !!}<br>


{!! Form::label('plural', 'Plural *') !!}<br>
{!! Form::text('plural', null, ['class'=>"form-control width-grande <% validar(optionAbrangencia.plural) %>", 'ng-model'=>'optionAbrangencia.plural', 'ng-required'=>'true', 'init-model'=>'optionAbrangencia.plural', 'placeholder' => '']) !!}<br>

