{{--É NECESSÁRIO RODAR O COMANDO composer require illuminate/html E ALTERAR ACRESCENTAR LINHA NO ARQUIVO config/app.php--}}


{!! Form::label('titulo', 'Título *') !!}<br>
{!! Form::text('titulo', null, ['class'=>"form-control width-grande <% validar(indicador.titulo) %>", 'ng-model'=>'indicador.titulo', 'ng-required'=>'true', 'init-model'=>'indicador.titulo', 'placeholder' => '']) !!}<br>


