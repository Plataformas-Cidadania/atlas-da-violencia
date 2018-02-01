{{--É NECESSÁRIO RODAR O COMANDO composer require illuminate/html E ALTERAR ACRESCENTAR LINHA NO ARQUIVO config/app.php--}}



{!! Form::label('titulo', 'Título *') !!}<br>
{!! Form::text('titulo', null, ['class'=>"form-control width-grande <% validar(unidade.titulo) %>", 'ng-model'=>'unidade.titulo', 'ng-required'=>'true', 'init-model'=>'unidade.titulo', 'placeholder' => '']) !!}<br>

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
