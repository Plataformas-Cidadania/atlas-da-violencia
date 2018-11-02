@if(empty($tema))
    {!! Form::label('idioma_sigla', 'Idioma *') !!}<br>
    {!! Form::select('idioma_sigla',
            $idiomas,
    null, ['class'=>"form-control width-medio <% validar(idioma.idioma_sigla) %>", 'ng-model'=>'idioma.idioma_sigla', 'ng-required'=>'true', 'init-model'=>'idioma.idioma_sigla', 'placeholder' => 'Selecione']) !!}<br>
@endif
{{--

{!! Form::label('tema_id', 'Temas *') !!}<br>
{!! Form::select('tema_id',
        $temas,
null, ['class'=>"form-control width-medio <% validar(tema.tema_id) %>", 'ng-model'=>'tema.tema_id', 'init-model'=>'tema.tema_id', 'placeholder' => 'Principal']) !!}<br>
--}}

{!! Form::hidden('tema_id', $tema_id, ['class'=>"form-control width-grande <% validar(tema.tema_id) %>", 'ng-model'=>'tema.tema_id', 'ng-required'=>'true', 'init-model'=>'tema.tema_id', 'placeholder' => '']) !!}<br>

@if(empty($tema))
{!! Form::label('titulo', 'Tema *') !!}<br>
{!! Form::text('titulo', null, ['class'=>"form-control width-grande <% validar(idioma.titulo) %>", 'ng-model'=>'idioma.titulo', 'ng-required'=>'true', 'init-model'=>'idioma.titulo', 'placeholder' => '']) !!}<br>
@endif