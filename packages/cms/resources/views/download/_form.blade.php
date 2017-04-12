{{--É NECESSÁRIO RODAR O COMANDO composer require illuminate/html E ALTERAR ACRESCENTAR LINHA NO ARQUIVO config/app.php--}}

{!! Form::label('idioma_sigla', 'Idioma *') !!}<br>
{!! Form::select('idioma_sigla',
        $idiomas,
null, ['class'=>"form-control width-medio <% validar(download.idioma_sigla) %>", 'ng-model'=>'download.idioma_sigla', 'ng-required'=>'true', 'init-model'=>'download.idioma_sigla', 'placeholder' => 'Selecione']) !!}<br>

{!! Form::label('origem_id', 'Origem *') !!}<br>
{!! Form::select('origem_id',
        $series,
null, ['class'=>"form-control width-medio <% validar(download.origem_id) %>", 'ng-model'=>'download.origem_id', 'ng-required'=>'true', 'init-model'=>'download.origem_id', 'placeholder' => 'Selecione']) !!}<br>

{!! Form::label('titulo', 'Título *') !!}<br>
{!! Form::text('titulo', null, ['class'=>"form-control width-grande <% validar(download.titulo) %>", 'ng-model'=>'download.titulo', 'ng-required'=>'true', 'init-model'=>'download.titulo', 'placeholder' => '']) !!}<br>

{!! Form::label('descricao', 'Descrição *') !!}<br>
{!! Form::textarea('descricao', null, ['class'=>"form-control width-grande <% validar(download.descricao) %>", 'ui-tinymce'=>'tinymceOptions', 'ng-model'=>'download.descricao', 'init-model'=>'download.descricao']) !!}<br>

