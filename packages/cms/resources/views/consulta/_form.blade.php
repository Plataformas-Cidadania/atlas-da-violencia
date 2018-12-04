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
null, ['class'=>"form-control width-medio <% validar(consulta.tema) %>", 'ng-model'=>'consulta.tema_id', 'ng-required'=>'true', 'init-model'=>'consulta.tema_id', 'placeholder' => 'Selecione']) !!}<br>
--}}

{!! Form::label('periodicidade_id', 'Periodicidade *') !!}<br>
{!! Form::select('periodicidade_id',
        $periodicidades,
null, ['class'=>"form-control width-medio <% validar(consulta.periodicidade) %>", 'ng-model'=>'consulta.periodicidade_id', 'ng-required'=>'true', 'init-model'=>'consulta.periodicidade_id', 'placeholder' => 'Selecione']) !!}<br>

{!! Form::label('unidade_id', 'Unidades *') !!}<br>
{!! Form::select('unidade_id',
        $unidades,
null, ['class'=>"form-control width-medio <% validar(consulta.unidade) %>", 'ng-model'=>'consulta.unidade_id', 'ng-required'=>'true', 'init-model'=>'consulta.unidade_id', 'placeholder' => 'Selecione']) !!}<br>


{!! Form::label('titulo', 'Tema *') !!}<br>
{!! Form::text('titulo', null, ['class'=>"form-control width-grande <% validar(idioma.titulo) %>", 'ng-model'=>'idioma.titulo', 'ng-required'=>'true', 'init-model'=>'idioma.titulo', 'placeholder' => '']) !!}<br>

{{--
{!! Form::label('titulo', 'Título *') !!}<br>
{!! Form::text('titulo', null, ['class'=>"form-control width-grande <% validar(consulta.titulo) %>", 'ng-model'=>'consulta.titulo', 'ng-required'=>'true', 'init-model'=>'consulta.titulo', 'placeholder' => '']) !!}<br>
--}}

{!! Form::label('url', 'Link ') !!}<br>
{!! Form::text('url', null, ['class'=>"form-control width-grande <% validar(consulta.url) %>", 'ng-model'=>'consulta.url', 'init-model'=>'consulta.url', 'placeholder' => '']) !!}<br>


