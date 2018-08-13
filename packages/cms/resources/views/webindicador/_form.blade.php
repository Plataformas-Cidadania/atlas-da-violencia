{{--É NECESSÁRIO RODAR O COMANDO composer require illuminate/html E ALTERAR ACRESCENTAR LINHA NO ARQUIVO config/app.php--}}


{!! Form::label('titulo', 'Título *') !!}<br>
{!! Form::text('titulo', null, ['class'=>"form-control width-grande <% validar(webindicador.titulo) %>", 'ng-model'=>'webindicador.titulo', 'ng-required'=>'true', 'init-model'=>'webindicador.titulo', 'placeholder' => '']) !!}<br>

{!! Form::label('url', 'Link *') !!}<br>
{!! Form::text('url', null, ['class'=>"form-control width-grande <% validar(webindicador.url) %>", 'ng-model'=>'webindicador.url', 'ng-required'=>'true', 'init-model'=>'webindicador.url', 'placeholder' => '']) !!}<br>


