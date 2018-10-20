{!! Form::label('titulo', 'Titulo *') !!}<br>
{!! Form::text('titulo', null, ['class'=>"form-control width-grande <% validar(filtro.titulo) %>", 'ng-model'=>'filtro.titulo', 'ng-required'=>'true', 'init-model'=>'filtro.titulo', 'placeholder' => '']) !!}<br>
