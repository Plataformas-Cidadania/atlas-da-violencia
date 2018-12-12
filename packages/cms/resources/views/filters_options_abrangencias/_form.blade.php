{!! Form::hidden('option_abrangencia_id', $option_abrangencia_id, ['class'=>"form-control width-grande <% validar(optionAbrangencia.option_abrangencia_id) %>", 'ng-model'=>'optionAbrangencia.option_abrangencia_id', 'ng-required'=>'true', 'init-model'=>'optionAbrangencia.option_abrangencia_id', 'placeholder' => '']) !!}<br>

{!! Form::label('id', 'ID *') !!}<br>
{!! Form::text('id', null, ['class'=>"form-control width-grande <% validar(optionAbrangencia.id) %>", 'ng-model'=>'optionAbrangencia.id', 'ng-required'=>'true', 'init-model'=>'optionAbrangencia.id', 'placeholder' => '']) !!}<br>

{!! Form::label('title', 'Título *') !!}<br>
{!! Form::text('title', null, ['class'=>"form-control width-grande <% validar(optionAbrangencia.title) %>", 'ng-model'=>'optionAbrangencia.title', 'ng-required'=>'true', 'init-model'=>'optionAbrangencia.title', 'placeholder' => '']) !!}<br>
