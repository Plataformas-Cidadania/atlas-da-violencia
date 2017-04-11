{{--É NECESSÁRIO RODAR O COMANDO composer require illuminate/html E ALTERAR ACRESCENTAR LINHA NO ARQUIVO config/app.php--}}

{!! Form::label('idioma_id', 'Idioma *') !!}<br>
{!! Form::select('idioma_id',
        $idiomas,
null, ['class'=>"form-control width-medio <% validar(modulo.idioma_id) %>", 'ng-model'=>'modulo.idioma_id', 'ng-required'=>'true', 'init-model'=>'modulo.idioma_id', 'placeholder' => 'Selecione']) !!}<br>


{!! Form::label('idioma_id', 'Idioma *') !!}<br>
{!! Form::select('idioma_id',
        $idiomas,
null, ['class'=>"form-control width-medio <% validar(modulo.idioma_id) %>", 'ng-model'=>'modulo.idioma_id', 'ng-required'=>'true', 'init-model'=>'modulo.idioma_id', 'placeholder' => 'Selecione']) !!}<br>


{!! Form::hidden('tema_id', 1, ['class'=>"form-control width-grande <% validar(serie.tema_id) %>", 'ng-model'=>'serie.tema_id', 'ng-required'=>'true', 'init-model'=>'serie.tema_id', 'placeholder' => '']) !!}<br>

{!! Form::label('titulo', 'Título *') !!}<br>
{!! Form::text('titulo', null, ['class'=>"form-control width-grande <% validar(serie.titulo) %>", 'ng-model'=>'serie.titulo', 'ng-required'=>'true', 'init-model'=>'serie.titulo', 'placeholder' => '']) !!}<br>

{!! Form::label('descricao', 'Descrição *') !!}<br>
{!! Form::textarea('descricao', null, ['class'=>"form-control width-grande <% validar(serie.descricao) %>", 'ui-tinymce'=>'tinymceOptions', 'ng-model'=>'serie.descricao', 'init-model'=>'serie.descricao']) !!}<br>

<?php
    $periodicidades = ['Anual' => 'Anual', 'Mensal' => 'Mensal', 'Trimestral' => 'Trimestral'];
?>
{!! Form::label('periodicidade', 'Periodicidade *') !!}<br>
{!! Form::select('periodicidade',
        $periodicidades,
null, ['class'=>"form-control width-medio <% validar(serie.periodicidade) %>", 'ng-model'=>'serie.periodicidade', 'ng-required'=>'true', 'init-model'=>'serie.periodicidade', 'placeholder' => 'Selecione']) !!}<br>

{!! Form::label('fonte_id', 'Fonte *') !!}<br>
{!! Form::select('fonte_id',
        $fontes,
null, ['class'=>"form-control width-medio <% validar(serie.fonte_id) %>", 'ng-model'=>'serie.fonte_id', 'ng-required'=>'true', 'init-model'=>'serie.fonte_id', 'placeholder' => 'Selecione']) !!}<br>


{{--<input type="hidden" name="serie_id" ng-model="serie.serie_id" ng-init="serie.serie_id=0"/>--}}

{!! Form::label('serie_id', 'Séries *') !!}<br>
{!! Form::select('serie_id',
        $series_relacionado,
null, ['class'=>"form-control width-medio <% validar(serie.serie_id) %>", 'ng-model'=>'serie.serie_id', 'init-model'=>'serie.serie_id', 'placeholder' => 'Principal']) !!}<br>



