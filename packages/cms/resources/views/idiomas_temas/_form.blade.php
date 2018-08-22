{!! Form::hidden('tema_id', $tema_id, ['class'=>"form-control width-grande <% validar(tema.tema_id) %>", 'ng-model'=>'tema.tema_id', 'ng-required'=>'true', 'init-model'=>'tema.tema_id', 'placeholder' => '']) !!}<br>

{!! Form::label('idioma_sigla', 'Idioma *') !!}<br>
{!! Form::select('idioma_sigla',
        $idiomas,
null, ['class'=>"form-control width-medio <% validar(tema.idioma_sigla) %>", 'ng-model'=>'tema.idioma_sigla', 'ng-required'=>'true', 'init-model'=>'tema.idioma_sigla', 'placeholder' => 'Selecione']) !!}<br>

{!! Form::label('titulo', 'Título *') !!}<br>
{!! Form::text('titulo', null, ['class'=>"form-control width-grande <% validar(tema.titulo) %>", 'ng-model'=>'tema.titulo', 'ng-required'=>'true', 'init-model'=>'tema.titulo', 'placeholder' => '']) !!}<br>

