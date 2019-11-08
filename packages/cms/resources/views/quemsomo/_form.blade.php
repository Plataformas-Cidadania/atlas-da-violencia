<?php $rota = Route::getCurrentRoute()->getPath();?>

<?php

    if(empty($tipo_id)){
        $tipo_id = null;
    }

    if($rota == 'cms/quemsomo/{id}'){
        $origem_id = null;
    }else{
        $origem_id = 0;
    }
    if($tipo_id==1||$tipo_id==7||$tipo_id==8||$tipo_id==10){
        $origem_id = 1;
    }
?>

{!! Form::label('idioma_sigla', 'Idioma *') !!}<br>
{!! Form::select('idioma_sigla',
        $idiomas,
null, ['class'=>"form-control width-medio <% validar(quemsomo.idioma_sigla) %>", 'ng-model'=>'quemsomo.idioma_sigla', 'ng-required'=>'true', 'init-model'=>'quemsomo.idioma_sigla', 'placeholder' => 'Selecione']) !!}<br>

<div style="display: none;">
    {!! Form::label('tipo', 'Tipo *') !!}<br>
    {!! Form::select('tipo',
            array(
                '0' => 'Bem vindo',
                '1' => 'Institucional',
                '2' => 'Acessibilidade',
                '3' => 'Redirecionamento',
                '4' => 'Home Publicações',
                '5' => 'Indicadores',
                '6' => 'Menu',
                '7' => 'Equipe',
                '8' => 'Marca',
                '9' => 'Consultas',
                '10' => 'Api',
            ), $tipo_id, ['class'=>"form-control width-medio <% validar(quemsomo.tipo) %>", 'ng-model'=>'quemsomo.tipo', /*'ng-required'=>'true',*/ 'init-model'=>'quemsomo.tipo', 'placeholder' => '']) !!}<br>
</div>
<div style="display: none;">
{!! Form::label('origem_id', 'Origem *') !!}<br>
{!! Form::select('origem_id',
        array(
            '0' => 'Principal',
            '1' => 'Institucional'
        ), $origem_id, ['class'=>"form-control width-medio <% validar(quemsomo.origem_id) %>", 'ng-model'=>'quemsomo.origem_id', /*'ng-required'=>'true',*/ 'init-model'=>'quemsomo.origem_id', 'placeholder' => '']) !!}<br>
</div>

{!! Form::label('titulo', 'Título *') !!}<br>
{!! Form::text('titulo', null, ['class'=>"form-control width-grande <% validar(quemsomo.titulo) %>", 'ng-model'=>'quemsomo.titulo', 'ng-required'=>'true', 'init-model'=>'quemsomo.titulo', 'placeholder' => '']) !!}<br>

{!! Form::label('descricao', 'Descrição *') !!}<br>
{!! Form::textarea('descricao', null, ['class'=>"form-control width-grande <% validar(quemsomo.descricao) %>", 'ui-tinymce'=>'tinymceOptions', 'ng-model'=>'quemsomo.descricao', 'init-model'=>'quemsomo.descricao']) !!}<br>

{!! Form::label('posicao', 'Posição *') !!}<br>
{!! Form::text('posicao', null, ['class'=>"form-control width-grande <% validar(quemsomo.posicao) %>", 'ng-model'=>'quemsomo.posicao', 'ng-required'=>'true', 'init-model'=>'quemsomo.posicao', 'placeholder' => '']) !!}<br>

<div class="row">
    <div class="col-md-4">
        {!! Form::label('url', 'Url ') !!}<br>
        {!! Form::text('url', null, ['class'=>"form-control width-grande <% validar(quemsomo.url) %>", 'ng-model'=>'quemsomo.url', 'init-model'=>'quemsomo.url', 'placeholder' => '']) !!}<br>
    </div>
    {{--<div class="col-md-2">
        <label for="tipoUrl">TipoUrl</label>
        <select class="form-control" name="tipoUrl" id="tipoUrl" ng-model="tipoUrl" ng-change="changeTipoUrl()">
            <option value="0">Iframe</option>
            <option value="1">Link</option>
        </select>
    </div>--}}
</div>
