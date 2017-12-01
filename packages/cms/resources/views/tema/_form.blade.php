
<?php

?>
{{--

{!! Form::label('tema_id', 'Temas *') !!}<br>
{!! Form::select('tema_id',
        $temas,
null, ['class'=>"form-control width-medio <% validar(tema.tema_id) %>", 'ng-model'=>'tema.tema_id', 'init-model'=>'tema.tema_id', 'placeholder' => 'Principal']) !!}<br>
--}}

{!! Form::hidden('tema_id', $tema_id, ['class'=>"form-control width-grande <% validar(tema.tema_id) %>", 'ng-model'=>'tema.tema_id', 'ng-required'=>'true', 'init-model'=>'tema.tema_id', 'placeholder' => '']) !!}<br>




{!! Form::label('tema', 'Tema *') !!}<br>
{!! Form::text('tema', null, ['class'=>"form-control width-grande <% validar(tema.tema) %>", 'ng-model'=>'tema.tema', 'ng-required'=>'true', 'init-model'=>'tema.tema', 'placeholder' => '']) !!}<br>
