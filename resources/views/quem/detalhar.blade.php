@extends('layout')
@section('title', trans('links.about'))
@section('content')

    <div class="container">
        <h2 id="calendar" aria-label="@lang('links.about')">@lang('links.about')</h2>
        <div class="line_title bg-pri"></div>
        <br>
        <div class="row">
            <div class="col-md-3">
                <ul class="menu-vertical">
                @foreach($menus as $menu)
                    <li role="presentation"><a href="quem/{{$menu->id}}/{{clean($menu->titulo)}}" accesskey="q"@if($menu->id==$id) class="corrente" @endif style="clear: both;"><i class="fa fa-dot-circle-o" aria-hidden="true"></i> {{$menu->titulo}}</a></li>
                @endforeach
                </ul>
            </div>
            <div class="col-md-9">
                @if($quem->tipo=='7')
                    @include('quem.equipe')
                @elseif($quem->tipo=='8')
                    @include('quem.marca')
                @else
                    @if(!empty($quem->imagem))
                        <picture>
                            <source srcset="imagens/quemsomos/sm-{{$quem->imagem}}" media="(max-width: 468px)">
                            <source srcset="imagens/quemsomos/md-{{$quem->imagem}}" media="(max-width: 768px)">
                            <source srcset="imagens/quemsomos/md-{{$quem->imagem}}" class="img-responsive">
                            <img srcset="imagens/quemsomos/md-{{$quem->imagem}}" alt="Imagem sobre, {{$quem->titulo}}" title="Imagem sobre, {{$quem->titulo}}" class="align-img">
                        </picture>
                    @endif
                    <p ng-class="{'alto-contraste': altoContrasteAtivo}">{!!$quem->descricao!!}</p>
                @endif
                <style>
                    .img-user{
                        border-radius: 50%;
                        width: 40px;
                        border: solid 2px #CCCCCC;
                        margin-right: 10px;
                    }
                    .box-marca h5{
                        font-weight: bold;
                        font-size: 12px;
                    }
                    .box-marca div:nth-child(1){

                    }
                    .box-integrante{
                        float: left;
                        margin: 0 10px 0 0;
                    }
                    .img-user{
                        width:50px;
                        height: 50px;
                        margin: 5px 10px;
                        border: solid 2px #CCCCCC;
                    }
                </style>

            </div>
        </div>
    </div>
@endsection

