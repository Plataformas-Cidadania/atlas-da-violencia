{!! Form::label('titulo', 'Título *') !!}<br>
{!! Form::text('titulo', null, ['class'=>"form-control width-grande <% validar(author.titulo) %>", 'ng-model'=>'author.titulo', 'ng-required'=>'true', 'init-model'=>'author.titulo', 'placeholder' => '']) !!}<br>

