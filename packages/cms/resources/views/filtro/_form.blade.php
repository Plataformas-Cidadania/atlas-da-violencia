{!! Form::label('titulo', 'Titulo *') !!}<br>
{!! Form::text('titulo', null, ['class'=>"form-control width-grande <% validar(filtro.titulo) %>", 'ng-model'=>'filtro.titulo', 'ng-required'=>'true', 'init-model'=>'filtro.titulo', 'placeholder' => '', 'ng-keyup' => 'titleToSlug()']) !!}<br>

{!! Form::label('slug', 'Slug *') !!}<br>
{!! Form::text('slug', null, ['class'=>"form-control width-grande <% validar(filtro.slug) %>", 'ng-model'=>'filtro.slug', 'ng-required'=>'true', 'init-model'=>'filtro.slug', 'placeholder' => '']) !!}<br>
