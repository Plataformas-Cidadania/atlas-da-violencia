{{--<link rel="stylesheet" href="/lib/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="/lib/bootstrap/css/bootstrap-theme.min.css">

<link rel="stylesheet" href="/lib/font-awesome/css/font-awesome.min.css">--}}

{{--<link rel="stylesheet" href="/css/all.css">
<link rel="stylesheet" href="/css/app.css">--}}

{{--{!! Html::style('css/all.css') !!}
{!! Html::style('css/app.css') !!}--}}

<style>
<?php
    echo file_get_contents(public_path()."/css/all.css");
    echo file_get_contents(public_path()."/css/app.css");
    // \Illuminate\Support\Facades\File::get(public_path()."/css/all.css");
    //echo \Illuminate\Support\Facades\File::get(public_path()."/css/app.css");

?>
</style>

<style>
    .irs-slider {
        background: url(http://evbsb1052.ipea.gov.br/atlasviolencia/img/sprite-skin-flat.png) repeat-x;
    }
</style>