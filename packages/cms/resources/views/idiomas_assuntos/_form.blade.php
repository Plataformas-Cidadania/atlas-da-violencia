{!! Form::hidden('assunto_id', $assunto_id, ['class'=>"form-control width-grande <% validar(assunto.assunto_id) %>", 'ng-model'=>'assunto.assunto_id', 'ng-required'=>'true', 'init-model'=>'assunto.assunto_id', 'placeholder' => '']) !!}<br>

{!! Form::label('idioma_sigla', 'Idioma *') !!}<br>
{!! Form::select('idioma_sigla',
        $idiomas,
null, ['class'=>"form-control width-medio <% validar(assunto.idioma_sigla) %>", 'ng-model'=>'assunto.idioma_sigla', 'ng-required'=>'true', 'init-model'=>'assunto.idioma_sigla', 'placeholder' => 'Selecione']) !!}<br>

{!! Form::label('titulo', 'Título *') !!}<br>
{!! Form::text('titulo', null, ['class'=>"form-control width-grande <% validar(assunto.titulo) %>", 'ng-model'=>'assunto.titulo', 'ng-required'=>'true', 'init-model'=>'assunto.titulo', 'placeholder' => '']) !!}<br>

