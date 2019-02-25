{!! Form::hidden('api_id', $api_id, ['class'=>"form-control width-grande <% validar(api.api_id) %>", 'ng-model'=>'api.api_id', 'ng-required'=>'true', 'init-model'=>'api.api_id', 'placeholder' => '']) !!}<br>

{!! Form::label('idioma_sigla', 'Idioma *') !!}<br>
{!! Form::select('idioma_sigla',
        $idiomas,
null, ['class'=>"form-control width-medio <% validar(api.idioma_sigla) %>", 'ng-model'=>'api.idioma_sigla', 'ng-required'=>'true', 'init-model'=>'api.idioma_sigla', 'placeholder' => 'Selecione']) !!}<br>

{!! Form::label('titulo', 'Título *') !!}<br>
{!! Form::text('titulo', null, ['class'=>"form-control width-grande <% validar(api.titulo) %>", 'ng-model'=>'api.titulo', 'ng-required'=>'true', 'init-model'=>'api.titulo', 'placeholder' => '']) !!}<br>

{!! Form::label('descricao', 'Parâmetros *') !!}<br>
{!! Form::text('descricao', null, ['class'=>"form-control width-grande <% validar(api.descricao) %>", 'ui-tinymce'=>'tinymceOptions', 'ng-model'=>'api.descricao', 'ng-required'=>'true', 'init-model'=>'api.descricao', 'placeholder' => '']) !!}<br>

