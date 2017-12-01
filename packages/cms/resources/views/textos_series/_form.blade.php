{!! Form::hidden('serie_id', $serie_id, ['class'=>"form-control width-grande <% validar(serie.serie_id) %>", 'ng-model'=>'serie.serie_id', 'ng-required'=>'true', 'init-model'=>'serie.serie_id', 'placeholder' => '']) !!}<br>

{!! Form::label('idioma_sigla', 'Idioma *') !!}<br>
{!! Form::select('idioma_sigla',
        $idiomas,
null, ['class'=>"form-control width-medio <% validar(serie.idioma_sigla) %>", 'ng-model'=>'serie.idioma_sigla', 'ng-required'=>'true', 'init-model'=>'serie.idioma_sigla', 'placeholder' => 'Selecione']) !!}<br>

{!! Form::label('titulo', 'Título *') !!}<br>
{!! Form::text('titulo', null, ['class'=>"form-control width-grande <% validar(serie.titulo) %>", 'ng-model'=>'serie.titulo', 'ng-required'=>'true', 'init-model'=>'serie.titulo', 'placeholder' => '']) !!}<br>

{!! Form::label('descricao', 'Metadados *') !!}<br>
{!! Form::textarea('descricao', null, ['class'=>"form-control width-grande <% validar(serie.descricao) %>", 'ui-tinymce'=>'tinymceOptions', 'ng-model'=>'serie.descricao', 'init-model'=>'serie.descricao']) !!}<br>

