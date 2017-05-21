{!! Form::label('idioma_sigla', 'Idioma *') !!}<br>
{!! Form::select('idioma_sigla',
        $idiomas,
null, ['class'=>"form-control width-medio <% validar(artwork.idioma_sigla) %>", 'ng-model'=>'artwork.idioma_sigla', 'ng-required'=>'true', 'init-model'=>'artwork.idioma_sigla', 'placeholder' => 'Selecione']) !!}<br>

{!! Form::label('title', 'Título *') !!}<br>
{!! Form::text('title', null, ['class'=>"form-control width-grande <% validar(artwork.title) %>", 'ng-model'=>'artwork.title', 'ng-required'=>'true', 'init-model'=>'artwork.title', 'placeholder' => '']) !!}<br>

{!! Form::label('version', 'Version *') !!}<br>
{!! Form::select('version',
        array(
            '0' => 'Horizontal',
            '1' => 'Vertical'
        ),
null, ['class'=>"form-control width-medio <% validar(artwork.version) %>", 'ng-model'=>'artwork.version', 'ng-required'=>'true', 'init-model'=>'artwork.version', 'placeholder' => '']) !!}<br>

{!! Form::label('format', 'Format *') !!}<br>
{!! Form::select('format',
        array(
            'png' => 'PNG',
            'jpg' => 'JPG'
        ),
null, ['class'=>"form-control width-medio <% validar(artwork.format) %>", 'ng-model'=>'artwork.format', 'ng-required'=>'true', 'init-model'=>'artwork.format', 'placeholder' => '']) !!}<br>
