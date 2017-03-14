{{--É NECESSÁRIO RODAR O COMANDO composer require illuminate/html E ALTERAR ACRESCENTAR LINHA NO ARQUIVO config/app.php--}}

{!! Form::label('link', 'Link artigo') !!}<br>
{!! Form::text('link', null, ['class'=>"form-control width-grande <% validar(artigo.link) %>", 'ng-model'=>'artigo.link',  'init-model'=>'artigo.link', 'placeholder' => '']) !!}<br>


{!! Form::label('origem_id', 'Origem *') !!}<br>
{!! Form::select('origem_id',
        $links,
null, ['class'=>"form-control width-medio <% validar(artigo.origem_id) %>", 'ng-model'=>'artigo.origem_id', 'ng-required'=>'true', 'init-model'=>'artigo.origem_id', 'placeholder' => 'Selecione']) !!}<br>


{!! Form::label('titulo', 'Título *') !!}<br>
{!! Form::text('titulo', null, ['class'=>"form-control width-grande <% validar(artigo.titulo) %>", 'ng-model'=>'artigo.titulo', 'ng-required'=>'true', 'init-model'=>'artigo.titulo', 'placeholder' => '']) !!}<br>

{!! Form::label('descricao', 'Descrição *') !!}<br>
{!! Form::textarea('descricao', null, ['class'=>"form-control width-grande <% validar(artigo.descricao) %>", 'ui-tinymce'=>'tinymceOptions', 'ng-model'=>'artigo.descricao', 'init-model'=>'artigo.descricao']) !!}<br>

{{--
{!! Form::label('autor', 'Autor *') !!}<br>
{!! Form::select('autor',
        $authors,
null, ['class'=>"form-control width-medio <% validar(artigo.autor) %>", 'ng-model'=>'artigo.autor', 'ng-required'=>'true', 'init-model'=>'artigo.autor', 'placeholder' => 'Selecione']) !!}<br>
--}}
<p><strong>Autores</strong></p>
@foreach($authors as $id => $autor)
    <div class="checkbox-inline">
        {!! Form::checkbox('autor'.$id, true, null, ['class'=>"checkbox-inline width-grande <% validar(author_artigo.autor_$id) %>", 'ng-model'=>"author_artigo.autor_$id", 'init-model'=>"author_artigo.autor_$id", 'style'=>"width: 30px; height: 30px;"]) !!}
        {!! Form::label('autor'.$id, $autor, ['style'=>"padding: 8px 20px 0 20px;"]) !!}
    </div>
@endforeach
<br><br>

<div class="row">
    <div class="col-md-6">
        {!! Form::label('fonte', 'Fonte') !!}<br>
        {!! Form::text('fonte', null, ['class'=>"form-control width-grande <% validar(artigo.fonte) %>", 'ng-model'=>'artigo.fonte', 'init-model'=>'artigo.fonte', 'placeholder' => '']) !!}<br>
    </div>
    <div class="col-md-6">
        {!! Form::label('link_font', 'Link') !!}<br>
        {!! Form::text('link_font', null, ['class'=>"form-control width-grande <% validar(artigo.link_font) %>", 'ng-model'=>'artigo.link_font', 'init-model'=>'artigo.link_font', 'placeholder' => '']) !!}<br>
    </div>
</div>

{!! Form::label('legenda', 'Legenda') !!}<br>
{!! Form::text('legenda', null, ['class'=>"form-control width-grande <% validar(artigo.legenda) %>", 'ng-model'=>'artigo.legenda',  'init-model'=>'artigo.legenda', 'placeholder' => '']) !!}<br>

