{!! Form::label('tema', 'Tema *') !!}<br>
{!! Form::text('tema', null, ['class'=>"form-control width-grande <% validar(fonte.tema) %>", 'ng-model'=>'fonte.tema', 'ng-required'=>'true', 'init-model'=>'fonte.tema', 'placeholder' => '']) !!}<br>
