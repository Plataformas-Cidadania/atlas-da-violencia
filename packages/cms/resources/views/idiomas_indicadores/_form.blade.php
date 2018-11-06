{!! Form::hidden('indicador_id', $indicador_id, ['class'=>"form-control width-grande <% validar(indicador.indicador_id) %>", 'ng-model'=>'indicador.indicador_id', 'ng-required'=>'true', 'init-model'=>'indicador.indicador_id', 'placeholder' => '']) !!}<br>

{!! Form::label('idioma_sigla', 'Idioma *') !!}<br>
{!! Form::select('idioma_sigla',
        $idiomas,
null, ['class'=>"form-control width-medio <% validar(indicador.idioma_sigla) %>", 'ng-model'=>'indicador.idioma_sigla', 'ng-required'=>'true', 'init-model'=>'indicador.idioma_sigla', 'placeholder' => 'Selecione']) !!}<br>

{!! Form::label('titulo', 'Título *') !!}<br>
{!! Form::text('titulo', null, ['class'=>"form-control width-grande <% validar(indicador.titulo) %>", 'ng-model'=>'indicador.titulo', 'ng-required'=>'true', 'init-model'=>'indicador.titulo', 'placeholder' => '']) !!}<br>

