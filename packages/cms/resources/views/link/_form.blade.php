{{--É NECESSÁRIO RODAR O COMANDO composer require illuminate/html E ALTERAR ACRESCENTAR LINHA NO ARQUIVO config/app.php--}}

{!! Form::label('posicao', 'Posição *') !!}<br>
{!! Form::number('posicao', null, ['class'=>"form-control width-pequeno <% validar(link.posicao) %>", 'ng-model'=>'link.posicao', 'ng-required'=>'true', 'init-model'=>'link.posicao', 'placeholder' => '']) !!}<br>

{!! Form::label('titulo', 'Título *') !!}<br>
{!! Form::text('titulo', null, ['class'=>"form-control width-grande <% validar(link.titulo) %>", 'ng-model'=>'link.titulo', 'ng-required'=>'true', 'init-model'=>'link.titulo', 'placeholder' => '']) !!}<br>

{{--{!! Form::label('descricao', 'Descrição *') !!}<br>
{!! Form::textarea('descricao', null, ['class'=>"form-control width-grande <% validar(link.descricao) %>", 'ng-model'=>'link.descricao', 'ng-trim'=>'false', 'maxlength'=>'250', 'init-model'=>'link.descricao']) !!}
<span>Caracteres restantes <% 250 - link.descricao.length %></span>
<br><br>--}}

{!! Form::label('link', 'Series *') !!}<br>
{!! Form::select('link',
        $series,
null, ['class'=>"form-control width-medio <% validar(link.link) %>", 'ng-model'=>'link.link', 'ng-required'=>'true', 'init-model'=>'link.link', 'placeholder' => 'Selecione']) !!}<br>

{!! Form::label('tags', 'Metas Tags *') !!}<br>
{!! Form::textarea('tags', null, ['class'=>"form-control width-grande <% validar(link.tags) %>", 'ng-model'=>'link.tags',  'init-model'=>'link.tags']) !!}
<br>