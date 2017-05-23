{{--É NECESSÁRIO RODAR O COMANDO composer require illuminate/html E ALTERAR ACRESCENTAR LINHA NO ARQUIVO config/app.php--}}

{!! Form::label('titulo', 'Título *') !!}<br>
{!! Form::text('titulo', null, ['class'=>"form-control width-grande <% validar(video.titulo) %>", 'ng-model'=>'video.titulo', 'ng-required'=>'true', 'init-model'=>'video.titulo', 'placeholder' => '']) !!}<br>

{!! Form::label('data', 'Data ') !!} 00/00/0000<br>
{!! Form::text('data', null, ['class'=>"form-control width-medio <% validar(video.data) %>", 'ng-model'=>'video.data', 'ng-required'=>'true', 'init-model'=>'video.data', 'placeholder' => '']) !!}<br>

{!! Form::label('link_video', 'Video (link do youtube)') !!}<br>
{!! Form::text('link_video', null, ['class'=>"form-control width-grande <% validar(video.link_video) %>", 'ng-model'=>'video.link_video', 'init-model'=>'video.link_video', 'placeholder' => '']) !!}<br>

{!! Form::label('posicao', 'Posição') !!}<br>
{!! Form::text('posicao', null, ['class'=>"form-control width-pequeno <% validar(video.posicao) %>", 'ng-model'=>'video.posicao',  'init-model'=>'video.posicao', 'placeholder' => '']) !!}<br>

{!! Form::label('descricao', 'Descrição *') !!}<br>
{!! Form::textarea('descricao', null, ['class'=>"form-control width-grande <% validar(video.descricao) %>", 'ui-tinymce'=>'tinymceOptions', 'ng-model'=>'video.descricao', 'init-model'=>'video.descricao']) !!}<br>

{!! Form::label('tags', 'Tags ') !!}<br>
{!! Form::text('tags', null, ['class'=>"form-control width-grande <% validar(video.tags) %>", 'ng-model'=>'video.tags', 'init-model'=>'video.tags', 'placeholder' => '']) !!}<br>
