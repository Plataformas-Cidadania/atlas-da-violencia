{!! Form::hidden('serie_id', $serie_id, ['class'=>"form-control width-grande <% validar(filtro.serie_id) %>", 'ng-model'=>'filtro.serie_id', 'ng-required'=>'true', 'init-model'=>'filtro.serie_id', 'placeholder' => '']) !!}<br>

{!! Form::label('filtro', 'Filtro *') !!}<br>
{!! Form::select('filtro_id',
        $filtros,
null, ['class'=>"form-control <% validar(filtro.filtro_id) %>", 'ng-model'=>'filtro.filtro_id', 'ng-change' => '', 'ng-required'=>'true', 'init-model'=>'filtro.filtro_id', 'placeholder' => 'Selecione']) !!}<br>

