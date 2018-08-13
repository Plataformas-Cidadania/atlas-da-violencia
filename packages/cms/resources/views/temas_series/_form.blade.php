{!! Form::hidden('serie_id', $serie_id, ['class'=>"form-control width-grande <% validar(tema.serie_id) %>", 'ng-model'=>'tema.serie_id', 'ng-required'=>'true', 'init-model'=>'tema.serie_id', 'placeholder' => '']) !!}<br>

{!! Form::label('tema', 'Tema *') !!}<br>
{!! Form::select('tema_id',
        $temas,
null, ['class'=>"form-control <% validar(tema.tema_id) %>", 'ng-model'=>'tema.tema_id', 'ng-change' => '', 'ng-required'=>'true', 'init-model'=>'tema.tema_id', 'placeholder' => 'Selecione']) !!}<br>

