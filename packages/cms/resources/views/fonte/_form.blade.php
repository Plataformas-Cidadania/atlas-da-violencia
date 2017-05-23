{!! Form::label('titulo', 'Titulo *') !!}<br>
{!! Form::text('titulo', null, ['class'=>"form-control width-grande <% validar(fonte.titulo) %>", 'ng-model'=>'fonte.titulo', 'ng-required'=>'true', 'init-model'=>'fonte.titulo', 'placeholder' => '']) !!}<br>
