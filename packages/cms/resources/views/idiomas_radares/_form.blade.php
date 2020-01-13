{!! Form::hidden('radar_id', $radar_id, ['class'=>"form-control width-grande <% validar(radar.radar_id) %>", 'ng-model'=>'radar.radar_id', 'ng-required'=>'true', 'init-model'=>'radar.radar_id', 'placeholder' => '']) !!}<br>

{!! Form::label('idioma_sigla', 'Idioma *') !!}<br>
{!! Form::select('idioma_sigla',
        $idiomas,
null, ['class'=>"form-control width-medio <% validar(radar.idioma_sigla) %>", 'ng-model'=>'radar.idioma_sigla', 'ng-required'=>'true', 'init-model'=>'radar.idioma_sigla', 'placeholder' => 'Selecione']) !!}<br>

{!! Form::label('titulo', 'Título *') !!}<br>
{!! Form::text('titulo', null, ['class'=>"form-control width-grande <% validar(radar.titulo) %>", 'ng-model'=>'radar.titulo', 'ng-required'=>'true', 'init-model'=>'radar.titulo', 'placeholder' => '']) !!}<br>

