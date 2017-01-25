{{--É NECESSÁRIO RODAR O COMANDO composer require illuminate/html E ALTERAR ACRESCENTAR LINHA NO ARQUIVO config/app.php--}}



{!! Form::label('titulo', 'Título *') !!}<br>
{!! Form::text('titulo', null, ['class'=>"form-control width-grande <% validar(serie.titulo) %>", 'ng-model'=>'serie.titulo', 'ng-required'=>'true', 'init-model'=>'serie.titulo', 'placeholder' => '']) !!}<br>

{!! Form::label('descricao', 'Descrição *') !!}<br>
{!! Form::textarea('descricao', null, ['class'=>"form-control width-grande <% validar(serie.descricao) %>", 'ui-tinymce'=>'tinymceOptions', 'ng-model'=>'serie.descricao', 'init-model'=>'serie.descricao']) !!}<br>

{!! Form::label('autor', 'Autor') !!}<br>
{!! Form::text('autor', null, ['class'=>"form-control width-grande <% validar(serie.autor) %>", 'ng-model'=>'serie.autor', 'init-model'=>'serie.autor', 'placeholder' => '']) !!}<br>

<div class="row">
    <div class="col-md-6">
        {!! Form::label('fonte', 'Fonte') !!}<br>
        {!! Form::text('fonte', null, ['class'=>"form-control width-grande <% validar(serie.fonte) %>", 'ng-model'=>'serie.fonte', 'init-model'=>'serie.fonte', 'placeholder' => '']) !!}<br>
    </div>
    <div class="col-md-6">
        {!! Form::label('link_font', 'Link') !!}<br>
        {!! Form::text('link_font', null, ['class'=>"form-control width-grande <% validar(serie.link_font) %>", 'ng-model'=>'serie.link_font', 'init-model'=>'serie.link_font', 'placeholder' => '']) !!}<br>
    </div>
</div>

