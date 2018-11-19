@if(empty($tema))
    {!! Form::label('idioma_sigla', 'Idioma *') !!}<br>
    {!! Form::select('idioma_sigla',
            $idiomas,
    null, ['class'=>"form-control width-medio <% validar(idioma.idioma_sigla) %>", 'ng-model'=>'idioma.idioma_sigla', 'ng-required'=>'true', 'init-model'=>'idioma.idioma_sigla', 'placeholder' => 'Selecione']) !!}<br>
@endif

{!! Form::label('titulo', 'Título *') !!}<br>
{!! Form::text('titulo', null, ['class'=>"form-control width-grande <% validar(idioma.titulo) %>", 'ng-model'=>'idioma.titulo', 'ng-required'=>'true', 'init-model'=>'idioma.titulo', 'placeholder' => '']) !!}<br>

<?php
$tipos = [
    '1' => 'Inteiro',
    '2' => 'Flutuante',
    '3' => 'Porcentagem'
];
?>

{!! Form::label('tipo', 'Tipos de Valores *') !!}<br>
{!! Form::select('tipo',
        $tipos,
null, ['class'=>"form-control width-medio <% validar(unidade.tipo) %>", 'ng-model'=>'unidade.tipo', 'ng-required'=>'true', 'init-model'=>'unidade.tipo', 'placeholder' => 'Selecione']) !!}<br>
