{{--<div style="display: none;">
{!! Form::label('idioma_id', 'Idioma *') !!}<br>
{!! Form::select('idioma_id',
        $idiomas,
null, ['class'=>"form-control width-medio <% validar(parceiro.idioma_id) %>", 'ng-model'=>'parceiro.idioma_id', 'init-model'=>'parceiro.idioma_id']) !!}<br>
</div>--}}


{!! Form::label('titulo', 'Título *') !!}<br>
{!! Form::text('titulo', null, ['class'=>"form-control width-grande <% validar(parceiro.titulo) %>", 'ng-model'=>'parceiro.titulo', 'ng-required'=>'true', 'init-model'=>'parceiro.titulo', 'placeholder' => '']) !!}<br>

{!! Form::label('descricao', 'Descrição') !!}<br>
{!! Form::textarea('descricao', null, ['class'=>"form-control width-grande <% validar(parceiro.descricao) %>", 'ui-tinymce'=>'tinymceOptions',  'ng-model'=>'parceiro.descricao', 'init-model'=>'parceiro.descricao']) !!}<br>

{!! Form::label('url', 'Link*') !!}<br>
{!! Form::text('url', null, ['class'=>"form-control width-grande <% validar(parceiro.url) %>", 'ng-model'=>'parceiro.url', 'ng-required'=>'true',  'init-model'=>'parceiro.url', 'placeholder' => '']) !!}<br>

{!! Form::label('posicao', 'Posição *') !!}<br>
{!! Form::text('posicao', null, ['class'=>"form-control width-pequeno <% validar(parceiro.posicao) %>", 'ng-model'=>'parceiro.posicao', 'ng-required'=>'true', 'init-model'=>'parceiro.posicao', 'placeholder' => '']) !!}<br>
