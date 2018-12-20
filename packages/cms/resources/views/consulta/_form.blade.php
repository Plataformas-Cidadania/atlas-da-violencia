@if(empty($consulta))
{!! Form::label('idioma_sigla', 'Idioma *') !!}<br>
{!! Form::select('idioma_sigla',
        $idiomas,
null, ['class'=>"form-control width-medio <% validar(idioma.idioma_sigla) %>", 'ng-model'=>'idioma.idioma_sigla', 'ng-required'=>'true', 'init-model'=>'idioma.idioma_sigla', 'placeholder' => 'Selecione']) !!}<br>

{!! Form::label('titulo', 'Título *') !!}<br>
{!! Form::text('titulo', null, ['class'=>"form-control width-grande <% validar(idioma.titulo) %>", 'ng-model'=>'idioma.titulo', 'ng-required'=>'true', 'init-model'=>'idioma.titulo', 'placeholder' => '']) !!}<br>
@endif

{!! Form::label('url', 'Link ') !!}<br>
{!! Form::text('url', null, ['class'=>"form-control width-grande <% validar(consulta.url) %>", 'ng-model'=>'consulta.url', 'init-model'=>'consulta.url', 'placeholder' => '']) !!}<br>


{{--
{!! Form::label('posicao', 'Posição *') !!}<br>
{!! Form::text('posicao', null, ['class'=>"form-control width-pequeno <% validar(consulta.posicao) %>", 'ng-model'=>'consulta.posicao', 'ng-required'=>'true', 'init-model'=>'consulta.posicao', 'placeholder' => '']) !!}<br>
--}}

