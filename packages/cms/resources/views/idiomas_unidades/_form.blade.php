{!! Form::hidden('unidade_id', $unidade_id, ['class'=>"form-control width-grande <% validar(unidade.unidade_id) %>", 'ng-model'=>'unidade.unidade_id', 'ng-required'=>'true', 'init-model'=>'unidade.unidade_id', 'placeholder' => '']) !!}<br>

{!! Form::label('idioma_sigla', 'Idioma *') !!}<br>
{!! Form::select('idioma_sigla',
        $idiomas,
null, ['class'=>"form-control width-medio <% validar(unidade.idioma_sigla) %>", 'ng-model'=>'unidade.idioma_sigla', 'ng-required'=>'true', 'init-model'=>'unidade.idioma_sigla', 'placeholder' => 'Selecione']) !!}<br>

{!! Form::label('titulo', 'Título *') !!}<br>
{!! Form::text('titulo', null, ['class'=>"form-control width-grande <% validar(unidade.titulo) %>", 'ng-model'=>'unidade.titulo', 'ng-required'=>'true', 'init-model'=>'unidade.titulo', 'placeholder' => '']) !!}<br>

