{{--É NECESSÁRIO RODAR O COMANDO composer require illuminate/html E ALTERAR ACRESCENTAR LINHA NO ARQUIVO config/app.php--}}

{!! Form::label('titulo', 'Título *') !!}<br>
{!! Form::text('titulo', null, ['class'=>"form-control width-grande <% validar(download.titulo) %>", 'ng-model'=>'download.titulo', 'ng-required'=>'true', 'init-model'=>'download.titulo', 'placeholder' => '']) !!}<br>

{!! Form::label('descricao', 'Descrição *') !!}<br>
{!! Form::textarea('descricao', null, ['class'=>"form-control width-grande <% validar(download.descricao) %>", 'ui-tinymce'=>'tinymceOptions', 'ng-model'=>'download.descricao', 'init-model'=>'download.descricao']) !!}<br>

