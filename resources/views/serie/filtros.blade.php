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
    </style>

    <div class="container">
        <div id="series"><div>
    </div>
@endsection
