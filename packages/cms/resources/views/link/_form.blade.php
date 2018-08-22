{{--É NECESSÁRIO RODAR O COMANDO composer require illuminate/html E ALTERAR ACRESCENTAR LINHA NO ARQUIVO config/app.php--}}

{!! Form::label('idioma_sigla', 'Idioma *') !!}<br>
{!! Form::select('idioma_sigla',
        $idiomas,
null, ['class'=>"form-control width-medio <% validar(link.idioma_sigla) %>", 'ng-model'=>'link.idioma_sigla', 'ng-required'=>'true', 'init-model'=>'link.idioma_sigla', 'placeholder' => 'Selecione']) !!}<br>


{!! Form::label('tipo', 'Origem *') !!}<br>
{!! Form::select('tipo',
        array(
            '0' => 'Interno',
            '1' => 'Externo',
            '2' => 'Inativo'
        ),
null, ['class'=>"form-control width-medio <% validar(link.tipo) %>", 'ng-model'=>'link.tipo', 'ng-required'=>'true', 'init-model'=>'link.tipo', 'placeholder' => '']) !!}<br>


{!! Form::label('posicao', 'Posição *') !!}<br>
{!! Form::number('posicao', null, ['class'=>"form-control width-pequeno <% validar(link.posicao) %>", 'ng-model'=>'link.posicao', 'ng-required'=>'true', 'init-model'=>'link.posicao', 'placeholder' => '']) !!}<br>

{!! Form::label('titulo', 'Título *') !!}<br>
{!! Form::text('titulo', null, ['class'=>"form-control width-grande <% validar(link.titulo) %>", 'ng-model'=>'link.titulo', 'ng-required'=>'true', 'init-model'=>'link.titulo', 'placeholder' => '']) !!}<br>


{{--{!! Form::label('descricao', 'Descrição *') !!}<br>
{!! Form::textarea('descricao', null, ['class'=>"form-control width-grande <% validar(link.descricao) %>", 'ng-model'=>'link.descricao', 'ng-trim'=>'false', 'maxlength'=>'250', 'init-model'=>'link.descricao']) !!}
<span>Caracteres restantes <% 250 - link.descricao.length %></span>
<br><br>--}}

<div ng-if="link.tipo==0">
    {!! Form::label('link', 'Temas *') !!}<br>
    {!! Form::select('link',
        $temas,
null, ['class'=>"form-control width-medio <% validar(link.link) %>", 'ng-model'=>'link.link', 'ng-required'=>'true', 'init-model'=>'link.link', 'placeholder' => 'Selecione']) !!}<br>
</div>
<div ng-if="link.tipo==1">
    {!! Form::label('link', 'Link *') !!}<br>
    {!! Form::text('link', null, ['class'=>"form-control width-grande <% validar(link.link) %>", 'ng-model'=>'link.link', 'ng-required'=>'true', 'init-model'=>'link.link', 'placeholder' => '']) !!}<br>
</div>

{!! Form::label('tags', 'Metas Tags *') !!}<br>
{!! Form::textarea('tags', null, ['class'=>"form-control width-grande <% validar(link.tags) %>", 'ng-model'=>'link.tags',  'init-model'=>'link.tags']) !!}
<br>
