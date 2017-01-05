@extends('layout')
@section('title', 'Mapa')
@section('content')
    <style>
        #mapid { height: 600px; }
    </style>

    <div id="map"></div>
    <button onclick="getData()">Carregar</button>

@endsection