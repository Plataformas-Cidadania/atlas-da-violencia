{{--É NECESSÁRIO RODAR O COMANDO composer require illuminate/html E ALTERAR ACRESCENTAR LINHA NO ARQUIVO config/app.php--}}

{!! Form::label('idioma_sigla', 'Idioma *') !!}<br>
{!! Form::select('idioma_sigla',
        $idiomas,
null, ['class'=>"form-control width-medio <% validar(quemsomo.idioma_sigla) %>", 'ng-model'=>'quemsomo.idioma_sigla', 'ng-required'=>'true', 'init-model'=>'quemsomo.idioma_sigla', 'placeholder' => 'Selecione']) !!}<br>


{!! Form::label('tipo', 'Tipo *') !!}<br>
{!! Form::select('tipo',
        array(
            '0' => 'Bem vindo',
            '1' => 'Institucional',
            '2' => 'Acessibilidade',
            '3' => 'Redirecionamento',
            '4' => 'Home Publicações'
        ),
null, ['class'=>"form-control width-medio <% validar(quemsomo.tipo) %>", 'ng-model'=>'quemsomo.tipo', 'ng-required'=>'true', 'init-model'=>'quemsomo.tipo', 'placeholder' => '']) !!}<br>

{!! Form::label('origem_id', 'Origem *') !!}<br>
{!! Form::select('origem_id',
        array(
            '0' => 'Principal',
            '1' => 'Quem somos'
        ),
null, ['class'=>"form-control width-medio <% validar(quemsomo.origem_id) %>", 'ng-model'=>'quemsomo.origem_id', 'ng-required'=>'true', 'init-model'=>'quemsomo.origem_id', 'placeholder' => '']) !!}<br>


{!! Form::label('titulo', 'Título *') !!}<br>
{!! Form::text('titulo', null, ['class'=>"form-control width-grande <% validar(quemsomo.titulo) %>", 'ng-model'=>'quemsomo.titulo', 'ng-required'=>'true', 'init-model'=>'quemsomo.titulo', 'placeholder' => '']) !!}<br>

{!! Form::label('descricao', 'Descrição *') !!}<br>
{!! Form::textarea('descricao', null, ['class'=>"form-control width-grande <% validar(quemsomo.descricao) %>", 'ui-tinymce'=>'tinymceOptions', 'ng-model'=>'quemsomo.descricao', 'init-model'=>'quemsomo.descricao']) !!}<br>

{!! Form::label('posicao', 'Posição *') !!}<br>
{!! Form::text('posicao', null, ['class'=>"form-control width-grande <% validar(quemsomo.posicao) %>", 'ng-model'=>'quemsomo.posicao', 'ng-required'=>'true', 'init-model'=>'quemsomo.posicao', 'placeholder' => '']) !!}<br>


