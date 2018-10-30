{{--É NECESSÁRIO RODAR O COMANDO composer require illuminate/html E ALTERAR ACRESCENTAR LINHA NO ARQUIVO config/app.php--}}

{!! Form::label('idioma_sigla', 'Idioma *') !!}<br>
{!! Form::select('idioma_sigla',
        $idiomas,
null, ['class'=>"form-control width-medio <% validar(menu.idioma_sigla) %>", 'ng-model'=>'menu.idioma_sigla', 'ng-required'=>'true', 'init-model'=>'menu.idioma_sigla', 'placeholder' => 'Selecione']) !!}<br>

{!! Form::label('title', 'Título *') !!}<br>
{!! Form::text('title', null, ['class'=>"form-control width-grande <% validar(menu.title) %>", 'ng-model'=>'menu.title', 'ng-required'=>'true', 'init-model'=>'menu.title', 'placeholder' => '']) !!}<br>

{!! Form::label('url', 'URL *') !!}<br>
{!! Form::text('url', null, ['class'=>"form-control width-grande <% validar(menu.url) %>", 'ng-model'=>'menu.url', 'ng-required'=>'true', 'init-model'=>'menu.url', 'placeholder' => '']) !!}<br>

{!! Form::label('posicao', 'Posição *') !!}<br>
{!! Form::text('posicao', null, ['class'=>"form-control width-grande <% validar(menu.posicao) %>", 'ng-model'=>'menu.posicao', 'ng-required'=>'true', 'init-model'=>'menu.posicao', 'placeholder' => '']) !!}<br>

{!! Form::label('accesskey', 'Accesskey *') !!}<br>
{!! Form::text('accesskey', null, ['class'=>"form-control width-grande <% validar(menu.accesskey) %>", 'ng-model'=>'menu.accesskey',  'init-model'=>'menu.accesskey', 'placeholder' => '']) !!}<br>

