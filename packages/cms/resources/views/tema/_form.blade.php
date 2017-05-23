{!! Form::label('tema', 'Tema *') !!}<br>
{!! Form::text('tema', null, ['class'=>"form-control width-grande <% validar(tema.tema) %>", 'ng-model'=>'tema.tema', 'ng-required'=>'true', 'init-model'=>'tema.tema', 'placeholder' => '']) !!}<br>
