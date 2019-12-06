@extends('layout')
@section('title', $download->titulo)
@section('content')

    <div class="container">
        <h1 class="h1_title" aria-label="{{$download->titulo}}, {{strip_tags($download->descricao)}}">@lang('pages.publication') Atlas {{$download->titulo}}</h1>
        <div class="line_title bg-pri"></div>
    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <iframe src="arquivos/downloads/{{$download->arquivo}}" height="1000" width="100%"  frameborder="0"></iframe>
            </div>
        </div>
    </div>
@endsection
