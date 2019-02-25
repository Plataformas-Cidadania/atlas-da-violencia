{!! Form::hidden('filtro_id', $filtro_id, ['ng-model'=>'valor.filtro_id', 'ng-required'=>'true', 'init-model'=>'valor.filtro_id', 'placeholder' => '']) !!}<br>

{!! Form::label('titulo', 'Título') !!}<br>
{!! Form::text('titulo', null, ['class'=>"form-control width-grande <% validar(valor.titulo) %>", 'ng-model'=>'valor.titulo', 'ng-required'=>'true', 'init-model'=>'valor.titulo', 'placeholder' => '']) !!}<br>
