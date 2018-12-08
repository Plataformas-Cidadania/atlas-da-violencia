{!! Form::hidden('periodicidade_id', $periodicidade_id, ['class'=>"form-control width-grande <% validar(periodicidade.periodicidade_id) %>", 'ng-model'=>'periodicidade.periodicidade_id', 'ng-required'=>'true', 'init-model'=>'periodicidade.periodicidade_id', 'placeholder' => '']) !!}<br>

{!! Form::label('idioma_sigla', 'Idioma *') !!}<br>
{!! Form::select('idioma_sigla',
        $idiomas,
null, ['class'=>"form-control width-medio <% validar(periodicidade.idioma_sigla) %>", 'ng-model'=>'periodicidade.idioma_sigla', 'ng-required'=>'true', 'init-model'=>'periodicidade.idioma_sigla', 'placeholder' => 'Selecione']) !!}<br>

{!! Form::label('titulo', 'Título *') !!}<br>
{!! Form::text('titulo', null, ['class'=>"form-control width-grande <% validar(periodicidade.titulo) %>", 'ng-model'=>'periodicidade.titulo', 'ng-required'=>'true', 'init-model'=>'periodicidade.titulo', 'placeholder' => '']) !!}<br>

