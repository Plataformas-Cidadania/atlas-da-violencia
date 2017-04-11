{{--É NECESSÁRIO RODAR O COMANDO composer require illuminate/html E ALTERAR ACRESCENTAR LINHA NO ARQUIVO config/app.php--}}

{!! Form::label('idioma_id', 'Idioma *') !!}<br>
{!! Form::select('idioma_id',
        $idiomas,
null, ['class'=>"form-control width-medio <% validar(modulo.idioma_id) %>", 'ng-model'=>'modulo.idioma_id', 'ng-required'=>'true', 'init-model'=>'modulo.idioma_id', 'placeholder' => 'Selecione']) !!}<br>


{!! Form::label('posicao', 'Posição *') !!}<br>
{!! Form::number('posicao', null, ['class'=>"form-control width-pequeno <% validar(indice.posicao) %>", 'ng-model'=>'indice.posicao', 'ng-required'=>'true', 'init-model'=>'indice.posicao', 'placeholder' => '', 'min' => '0']) !!}<br>

{!! Form::label('titulo', 'Título *') !!}<br>
{!! Form::text('titulo', null, ['class'=>"form-control width-grande <% validar(indice.titulo) %>", 'ng-model'=>'indice.titulo', 'ng-required'=>'true', 'init-model'=>'indice.titulo', 'placeholder' => '']) !!}<br>

{!! Form::label('valor', 'Valor *') !!}<br>
{!! Form::text('valor', null, ['class'=>"form-control width-grande <% validar(indice.valor) %>", 'ng-model'=>'indice.valor', 'ng-required'=>'true', 'init-model'=>'indice.valor', 'placeholder' => '']) !!}<br>

{!! Form::label('status', 'Status *') !!}<br>
{!! Form::select('status',
        array(
            '0' => 'Inativo',
            '1' => 'Ativo'
        ),
null, ['class'=>"form-control width-medio <% validar(indice.status) %>", 'ng-model'=>'indice.status', 'ng-required'=>'true', 'init-model'=>'indice.status', 'placeholder' => '']) !!}<br>
