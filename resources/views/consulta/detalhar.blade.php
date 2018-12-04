@extends('layout')
@section('title', "")
@section('content')
   <div class="container">
       <h1>{{$consulta->titulo}}</h1>
       <iframe
               @if($consulta->url)
               src="{{$consulta->url}}"
               @else
               src="arquivos/consultas/{{$consulta->arquivo}}"
               @endif
               frameborder="0" width="100%" height="1200">

       </iframe>
   </div>
@endsection
