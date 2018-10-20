{{--É NECESSÁRIO RODAR O COMANDO composer require illuminate/html E ALTERAR ACRESCENTAR LINHA NO ARQUIVO config/app.php--}}
{!! Form::label('idioma_sigla', 'Idioma *') !!}<br>
{!! Form::select('idioma_sigla',
        $idiomas,
null, ['class'=>"form-control width-medio <% validar(webindicador.idioma_sigla) %>", 'ng-model'=>'webindicador.idioma_sigla', 'ng-required'=>'true', 'init-model'=>'webindicador.idioma_sigla', 'placeholder' => 'Selecione']) !!}<br>


{!! Form::label('titulo', 'Título *') !!}<br>
{!! Form::text('titulo', null, ['class'=>"form-control width-grande <% validar(webindicador.titulo) %>", 'ng-model'=>'webindicador.titulo', 'ng-required'=>'true', 'init-model'=>'webindicador.titulo', 'placeholder' => '']) !!}<br>

{!! Form::label('url', 'Link ') !!}<br>
{!! Form::text('url', null, ['class'=>"form-control width-grande <% validar(webindicador.url) %>", 'ng-model'=>'webindicador.url', 'init-model'=>'webindicador.url', 'placeholder' => '']) !!}<br>


