@extends('layout')
@section('title', "")
@section('content')
   <div class="container">
       <h1>{{$consulta->titulo}}</h1>

       <br>
       {{--<a class="btn btn-info" href="downloads/2/{{$consulta->id}}">Downloads</a>
       <br>--}}

       <iframe
               @if($consulta->url)
               src="{{$consulta->url}}"
               @else
               src="arquivos/series/{{$consulta->arquivo}}"
               @endif
               frameborder="0" width="100%" height="1200">

       </iframe>
   </div>
@endsection
