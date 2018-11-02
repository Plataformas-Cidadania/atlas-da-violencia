{!! Form::label('idioma_sigla', 'Idioma *') !!}<br>
{!! Form::select('idioma_sigla',
        $idiomas,
null, ['class'=>"form-control width-medio <% validar(versao.idioma_sigla) %>", 'ng-model'=>'versao.idioma_sigla', 'ng-required'=>'true', 'init-model'=>'versao.idioma_sigla', 'placeholder' => 'Selecione']) !!}<br>


{!! Form::label('titulo', 'Título *') !!}<br>
{!! Form::text('titulo', null, ['class'=>"form-control width-grande <% validar(versao.titulo) %>", 'ng-model'=>'versao.titulo', 'ng-required'=>'true', 'init-model'=>'versao.titulo', 'placeholder' => '']) !!}<br>

{!! Form::label('descricao', 'Descrição *') !!}<br>
{!! Form::textarea('descricao', null, ['class'=>"form-control width-grande <% validar(versao.descricao) %>", 'ui-tinymce'=>'tinymceOptions', 'ng-model'=>'versao.descricao', 'init-model'=>'versao.descricao']) !!}<br>

{!! Form::label('posicao', 'Posição *') !!}<br>
{!! Form::text('posicao', null, ['class'=>"form-control width-grande <% validar(versao.posicao) %>", 'ng-model'=>'versao.posicao', 'ng-required'=>'true', 'init-model'=>'versao.posicao', 'placeholder' => '']) !!}<br>
