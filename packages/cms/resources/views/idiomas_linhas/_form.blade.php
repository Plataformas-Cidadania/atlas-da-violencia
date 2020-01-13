{!! Form::hidden('linha_id', $linha_id, ['class'=>"form-control width-grande <% validar(linha.linha_id) %>", 'ng-model'=>'linha.linha_id', 'ng-required'=>'true', 'init-model'=>'linha.linha_id', 'placeholder' => '']) !!}<br>

{!! Form::label('idioma_sigla', 'Idioma *') !!}<br>
{!! Form::select('idioma_sigla',
        $idiomas,
null, ['class'=>"form-control width-medio <% validar(linha.idioma_sigla) %>", 'ng-model'=>'linha.idioma_sigla', 'ng-required'=>'true', 'init-model'=>'linha.idioma_sigla', 'placeholder' => 'Selecione']) !!}<br>

{!! Form::label('titulo', 'Título *') !!}<br>
{!! Form::text('titulo', null, ['class'=>"form-control width-grande <% validar(linha.titulo) %>", 'ng-model'=>'linha.titulo', 'ng-required'=>'true', 'init-model'=>'linha.titulo', 'placeholder' => '']) !!}<br>

