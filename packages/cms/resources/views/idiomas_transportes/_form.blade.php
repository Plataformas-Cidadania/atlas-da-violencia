{!! Form::hidden('transporte_id', $transporte_id, ['class'=>"form-control width-grande <% validar(transporte.transporte_id) %>", 'ng-model'=>'transporte.transporte_id', 'ng-required'=>'true', 'init-model'=>'transporte.transporte_id', 'placeholder' => '']) !!}<br>

{!! Form::label('idioma_sigla', 'Idioma *') !!}<br>
{!! Form::select('idioma_sigla',
        $idiomas,
null, ['class'=>"form-control width-medio <% validar(transporte.idioma_sigla) %>", 'ng-model'=>'transporte.idioma_sigla', 'ng-required'=>'true', 'init-model'=>'transporte.idioma_sigla', 'placeholder' => 'Selecione']) !!}<br>

{!! Form::label('titulo', 'Título *') !!}<br>
{!! Form::text('titulo', null, ['class'=>"form-control width-grande <% validar(transporte.titulo) %>", 'ng-model'=>'transporte.titulo', 'ng-required'=>'true', 'init-model'=>'transporte.titulo', 'placeholder' => '']) !!}<br>

