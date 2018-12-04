@extends('layout')
@section('title', "")
@section('content')

    <style>

        input[type=checkbox] {
            display:none;
        }


        input[type=checkbox] + label{
            background: url("/img/checkbox_off.png") no-repeat;
            height: 22px;
            width: 22px;
            display:inline-block;
            padding: 0;
            cursor: pointer;
        }
        input[type=checkbox]:checked + label{
            background: url("/img/checkbox_on.png") no-repeat;
            height: 22px;
            width: 22px;
            display:inline-block;
            padding: 0;
        }
        label {
            display: inherit;
            max-width: 100%;
            margin-bottom: 0;
            font-weight: inherit;

        }
        .animacao{
            transition: all linear 0.5s;
            transition-duration: 1s;
        }

        fieldset{
            border: solid 1px #E4E4E4;
            border-radius: 5px;
        }

        legend{
            font-size: 13px;
            border: 0;
            width: inherit;
            padding: 5px;
            margin: 0 3px;
        }

        .div-options{
            border: solid 1px #ccc;
            padding: 8px;
            border-radius: 5px;
            -moz-border-radius: 5px;
            -webkit-border-radius: 5px;
            cursor: pointer;
        }

        .div-options i{
            float:right;
        }

    </style>
    <div class="container">
        <script>
            tema_id = "{{$id}}";
            {{--titulo = "{!! $tema->titulo !!}";--}}
            title="{{$content->titulo}}";
            description="{!! str_replace('"', '\"', preg_replace( "/\r|\n/", "", $content->descricao)) !!}";
            lang_inquiries = "@lang('react.inquiries')";
            lang_themes = "@lang('react.themes')";
            lang_documents = "@lang('react.documents')";
            lang_search_indicators   = "@lang('react.search-indicators')";
            lang_search_name   = "@lang('react.search-name')";
            lang_series = "@lang('react.series')";
            lang_unity = "@lang('react.unity')";
            lang_frequency = "@lang('react.frequency')";
            lang_no_results = "@lang('react.no-results')";
            lang_wait = "@lang('react.wait')";
            lang_select_themes = "@lang('react.select-themes')";
            filtroIndicadores = 0;
        </script>

        <div id="consultas"></div>
    </div>
@endsection
