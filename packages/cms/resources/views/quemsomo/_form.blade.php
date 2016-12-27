{{--É NECESSÁRIO RODAR O COMANDO composer require illuminate/html E ALTERAR ACRESCENTAR LINHA NO ARQUIVO config/app.php--}}

{!! Form::label('tipo', 'Tipo *') !!}<br>
{!! Form::select('tipo',
        array(
            '0' => 'Bem vindo',
            '1' => 'Quem somos',
            '2' => 'Acessibilidade'
        ),
null, ['class'=>"form-control width-medio <% validar(quemsomo.tipo) %>", 'ng-model'=>'quemsomo.tipo', 'ng-required'=>'true', 'init-model'=>'quemsomo.tipo', 'placeholder' => '']) !!}<br>


{!! Form::label('titulo', 'Título *') !!}<br>
{!! Form::text('titulo', null, ['class'=>"form-control width-grande <% validar(quemsomo.titulo) %>", 'ng-model'=>'quemsomo.titulo', 'ng-required'=>'true', 'init-model'=>'quemsomo.titulo', 'placeholder' => '']) !!}<br>

{!! Form::label('descricao', 'Descrição *') !!}<br>
{!! Form::textarea('descricao', null, ['class'=>"form-control width-grande <% validar(quemsomo.descricao) %>", 'ui-tinymce'=>'tinymceOptions', 'ng-model'=>'quemsomo.descricao', 'init-model'=>'quemsomo.descricao']) !!}<br>

