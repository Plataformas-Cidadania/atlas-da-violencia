{!! Form::label('padraoTerritorio', 'PadraoTerritorio *') !!}<br>
{!! Form::select('option_abrangencia_id',
        $optionsAbrangencias,
null, ['class'=>"form-control <% validar(padraoTerritorio.option_abrangencia_id) %>", 'ng-model'=>'padraoTerritorio.option_abrangencia_id', 'ng-change' => '', 'ng-required'=>'true', 'init-model'=>'padraoTerritorio.option_abrangencia_id', 'placeholder' => 'Selecione']) !!}<br>



{!! Form::label('territorios', 'Territorios *') !!}<br>
{!! Form::text('territorios', null, ['class'=>"form-control width-grande <% validar(padraoTerritorio.territorios) %>", 'ng-model'=>'padraoTerritorio.territorios', 'ng-required'=>'true', 'init-model'=>'padraoTerritorio.territorios', 'placeholder' => '']) !!}<br>
