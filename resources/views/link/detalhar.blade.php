@extends('layout')
@section('title', $link->titulo)
@section('content')

    <div class="container">
        <h1 class="h1_title" aria-label="{{$link->titulo}}, {{strip_tags($link->descricao)}}">{{$link->titulo}}</h1>
        <div class="line_title bg-pri"></div>
    </div>
    <div>
        <iframe src="{{$link->link}}" frameborder="0" width="100%" height="1000" scrolling="no"></iframe>
    </div>
@endsection
