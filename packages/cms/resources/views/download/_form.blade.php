{{--É NECESSÁRIO RODAR O COMANDO composer require illuminate/html E ALTERAR ACRESCENTAR LINHA NO ARQUIVO config/app.php--}}

{!! Form::label('idioma_id', 'Idioma *') !!}<br>
{!! Form::select('idioma_id',
        $idiomas,
null, ['class'=>"form-control width-medio <% validar(modulo.idioma_id) %>", 'ng-model'=>'modulo.idioma_id', 'ng-required'=>'true', 'init-model'=>'modulo.idioma_id', 'placeholder' => 'Selecione']) !!}<br>

{!! Form::label('origem_id', 'Origem *') !!}<br>
{!! Form::select('origem_id',
        $series,
null, ['class'=>"form-control width-medio <% validar(download.origem_id) %>", 'ng-model'=>'download.origem_id', 'ng-required'=>'true', 'init-model'=>'download.origem_id', 'placeholder' => 'Selecione']) !!}<br>

{!! Form::label('titulo', 'Título *') !!}<br>
{!! Form::text('titulo', null, ['class'=>"form-control width-grande <% validar(download.titulo) %>", 'ng-model'=>'download.titulo', 'ng-required'=>'true', 'init-model'=>'download.titulo', 'placeholder' => '']) !!}<br>

{!! Form::label('descricao', 'Descrição *') !!}<br>
{!! Form::textarea('descricao', null, ['class'=>"form-control width-grande <% validar(download.descricao) %>", 'ui-tinymce'=>'tinymceOptions', 'ng-model'=>'download.descricao', 'init-model'=>'download.descricao']) !!}<br>

