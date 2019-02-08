@extends('layout')
@section('title', trans('links.about'))
@section('content')

    <div class="container">
        <h2 id="calendar" aria-label="@lang('links.about')">@lang('links.about')</h2>
        <div class="line_title bg-pri"></div>
        <br>
        <div class="row">

            <div class="col-md-12">

                    @if(!empty($quem->imagem))
                        <picture>
                            <source srcset="imagens/quemsomos/sm-{{$quem->imagem}}" media="(max-width: 468px)">
                            <source srcset="imagens/quemsomos/md-{{$quem->imagem}}" media="(max-width: 768px)">
                            <source srcset="imagens/quemsomos/md-{{$quem->imagem}}" class="img-responsive">
                            <img srcset="imagens/quemsomos/md-{{$quem->imagem}}" alt="Imagem sobre, {{$quem->titulo}}" title="Imagem sobre, {{$quem->titulo}}" class="align-img">
                        </picture>
                    @endif
                    <p ng-class="{'alto-contraste': altoContrasteAtivo}">{!!$quem->descricao!!}</p>



            </div>
        </div>
    </div>
@endsection

