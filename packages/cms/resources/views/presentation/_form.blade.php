{!! Form::label('slug', 'Slug') !!}<br>
{!! Form::text('slug', null, ['class'=>"form-control width-grande <% validar(presentation.slug) %>", 'ng-model'=>'presentation.slug',  'init-model'=>'presentation.slug', 'placeholder' => '']) !!}<br>

