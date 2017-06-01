{{--<link rel="stylesheet" href="/lib/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="/lib/bootstrap/css/bootstrap-theme.min.css">

<link rel="stylesheet" href="/lib/font-awesome/css/font-awesome.min.css">--}}

{{--<link rel="stylesheet" href="/css/all.css">
<link rel="stylesheet" href="/css/app.css">--}}

{{--{!! Html::style('css/all.css') !!}
{!! Html::style('css/app.css') !!}--}}

<style>
<?php
    $base_href = config('app.url');

    echo file_get_contents(public_path()."/css/all.css");
    echo file_get_contents(public_path()."/css/app.css");
    // \Illuminate\Support\Facades\File::get(public_path()."/css/all.css");
    //echo \Illuminate\Support\Facades\File::get(public_path()."/css/app.css");

?>
</style>

<style>

    /*RANGER*/
    .irs-line-mid,
    .irs-line-left,
    .irs-line-right,
    .irs-bar,
    .irs-bar-edge,
    .irs-slider {
        background: url(http://<?php echo $base_href;?>img/sprite-skin-flat.png) repeat-x;
    }

    .irs {
        height: 40px;
    }
    .irs-with-grid {
        height: 60px;
    }
    .irs-line {
        height: 12px; top: 25px;
    }
    .irs-line-left {
        height: 12px;
        background-position: 0 -30px;
    }
    .irs-line-mid {
        height: 12px;
        background-position: 0 0;
    }
    .irs-line-right {
        height: 12px;
        background-position: 100% -30px;
    }

    .irs-bar {
        height: 12px; top: 25px;
        background-position: 0 -60px;
    }
    .irs-bar-edge {
        top: 25px;
        height: 12px; width: 9px;
        background-position: 0 -90px;
    }

    .irs-shadow {
        height: 3px; top: 34px;
        background: #000;
        opacity: 0.25;
    }
    .lt-ie9 .irs-shadow {
        filter: alpha(opacity=25);
    }

    .irs-slider {
        width: 16px; height: 18px;
        top: 22px;
        background-position: 0 -120px;
    }
    .irs-slider.state_hover, .irs-slider:hover {
        background-position: 0 -150px;
    }

    .irs-min, .irs-max {
        color: #999;
        font-size: 10px; line-height: 1.333;
        text-shadow: none;
        top: 0; padding: 1px 3px;
        background: #e1e4e9;
        -moz-border-radius: 4px;
        border-radius: 4px;
    }

    .irs-from, .irs-to, .irs-single {
        color: #fff;
        font-size: 10px; line-height: 1.333;
        text-shadow: none;
        padding: 1px 5px;
        background: #ed5565;
        -moz-border-radius: 4px;
        border-radius: 4px;
    }
    .irs-from:after, .irs-to:after, .irs-single:after {
        position: absolute; display: block; content: "";
        bottom: -6px; left: 50%;
        width: 0; height: 0;
        margin-left: -3px;
        overflow: hidden;
        border: 3px solid transparent;
        border-top-color: #ed5565;
    }


    .irs-grid-pol {
        background: #e1e4e9;
    }
    .irs-grid-text {
        color: #999;
    }

    .irs-disabled {
    }

</style>