{{--É NECESSÁRIO RODAR O COMANDO composer require illuminate/html E ALTERAR ACRESCENTAR LINHA NO ARQUIVO config/app.php--}}

{{--
{!! Form::label('idioma_sigla', 'Idioma *') !!}<br>
{!! Form::select('idioma_sigla',
        $idiomas,
null, ['class'=>"form-control width-medio <% validar(printing.idioma_sigla) %>", 'ng-model'=>'printing.idioma_sigla', 'ng-required'=>'true', 'init-model'=>'printing.idioma_sigla', 'placeholder' => 'Selecione']) !!}<br>
--}}

{!! Form::label('type', 'Type *') !!}<br>
{!! Form::select('type',
        array(
            '0' => 'Manual',
            '1' => 'Marca Horizontal',
            '2' => 'Marca vertical'
        ),
null, ['class'=>"form-control width-medio <% validar(printing.type) %>", 'ng-model'=>'printing.type', 'ng-required'=>'true', 'init-model'=>'printing.type', 'placeholder' => '']) !!}<br>


{!! Form::label('title', 'Título *') !!}<br>
{!! Form::text('title', null, ['class'=>"form-control width-grande <% validar(printing.title) %>", 'ng-model'=>'printing.title', 'ng-required'=>'true', 'init-model'=>'printing.title', 'placeholder' => '']) !!}<br>

{!! Form::label('description', 'Descrição *') !!}<br>
{!! Form::textarea('description', null, ['class'=>"form-control width-grande <% validar(printing.description) %>", 'ui-tinymce'=>'tinymceOptions', 'ng-model'=>'printing.description', 'init-model'=>'printing.description']) !!}<br>

