{{--É NECESSÁRIO RODAR O COMANDO composer require illuminate/html E ALTERAR ACRESCENTAR LINHA NO ARQUIVO config/app.php--}}



{!! Form::label('titulo', 'Título *') !!}<br>
{!! Form::text('titulo', null, ['class'=>"form-control width-grande <% validar(noticia.titulo) %>", 'ng-model'=>'noticia.titulo', 'ng-required'=>'true', 'init-model'=>'noticia.titulo', 'placeholder' => '']) !!}<br>

{!! Form::label('descricao', 'Descrição *') !!}<br>
{!! Form::textarea('descricao', null, ['class'=>"form-control width-grande <% validar(noticia.descricao) %>", 'ui-tinymce'=>'tinymceOptions', 'ng-model'=>'noticia.descricao', 'init-model'=>'noticia.descricao']) !!}<br>

{!! Form::label('autor', 'Autor') !!}<br>
{!! Form::text('autor', null, ['class'=>"form-control width-grande <% validar(noticia.autor) %>", 'ng-model'=>'noticia.autor', 'init-model'=>'noticia.autor', 'placeholder' => '']) !!}<br>

<div class="row">
    <div class="col-md-6">
        {!! Form::label('fonte', 'Fonte') !!}<br>
        {!! Form::text('fonte', null, ['class'=>"form-control width-grande <% validar(noticia.fonte) %>", 'ng-model'=>'noticia.fonte', 'init-model'=>'noticia.fonte', 'placeholder' => '']) !!}<br>
    </div>
    <div class="col-md-6">
        {!! Form::label('link_font', 'Link') !!}<br>
        {!! Form::text('link_font', null, ['class'=>"form-control width-grande <% validar(noticia.link_font) %>", 'ng-model'=>'noticia.link_font', 'init-model'=>'noticia.link_font', 'placeholder' => '']) !!}<br>
    </div>
</div>

