{{--É NECESSÁRIO RODAR O COMANDO composer require illuminate/html E ALTERAR ACRESCENTAR LINHA NO ARQUIVO config/app.php--}}

{!! Form::label('idioma_sigla', 'Idioma *') !!}<br>
{!! Form::select('idioma_sigla',
        $idiomas,
null, ['class'=>"form-control width-medio <% validar(destaque.idioma_sigla) %>", 'ng-model'=>'destaque.idioma_sigla', 'ng-required'=>'true', 'init-model'=>'destaque.idioma_sigla', 'placeholder' => 'Selecione']) !!}<br>


{!! Form::label('titulo', 'Título *') !!}<br>
{!! Form::text('titulo', null, ['class'=>"form-control width-grande <% validar(destaque.titulo) %>", 'ng-model'=>'destaque.titulo', 'ng-required'=>'true', 'init-model'=>'destaque.titulo', 'placeholder' => '']) !!}<br>

{!! Form::label('link', 'Link ') !!}<br>
{!! Form::text('link', null, ['class'=>"form-control width-grande <% validar(destaque.link) %>", 'ng-model'=>'destaque.link', 'init-model'=>'destaque.link', 'placeholder' => '']) !!}<br>

{!! Form::label('chamada', 'Chamada') !!}<br>
{!! Form::textarea('chamada', null, ['class'=>"form-control width-grande <% validar(destaque.chamada) %>", 'ui-tinymce'=>'tinymceOptions', 'ng-model'=>'destaque.chamada', 'init-model'=>'destaque.chamada']) !!}<br>





