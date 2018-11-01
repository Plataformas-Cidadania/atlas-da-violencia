{!! Form::hidden('consulta_id', $consulta_id, ['class'=>"form-control width-grande <% validar(consulta.consulta_id) %>", 'ng-model'=>'consulta.consulta_id', 'ng-required'=>'true', 'init-model'=>'consulta.consulta_id', 'placeholder' => '']) !!}<br>

{!! Form::label('idioma_sigla', 'Idioma *') !!}<br>
{!! Form::select('idioma_sigla',
        $idiomas,
null, ['class'=>"form-control width-medio <% validar(consulta.idioma_sigla) %>", 'ng-model'=>'consulta.idioma_sigla', 'ng-required'=>'true', 'init-model'=>'consulta.idioma_sigla', 'placeholder' => 'Selecione']) !!}<br>

{!! Form::label('titulo', 'Título *') !!}<br>
{!! Form::text('titulo', null, ['class'=>"form-control width-grande <% validar(consulta.titulo) %>", 'ng-model'=>'consulta.titulo', 'ng-required'=>'true', 'init-model'=>'consulta.titulo', 'placeholder' => '']) !!}<br>

