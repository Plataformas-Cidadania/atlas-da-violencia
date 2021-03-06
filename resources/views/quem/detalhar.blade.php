@extends('layout')
@section('title', trans('links.about'))
@section('content')

    <div class="container">
        <h1 id="calendar" aria-label="{{$quem->titulo}}">{{$quem->titulo}}</h1>
        <div class="line_title bg-pri"></div>
        <br>
        <div class="row">
            <?php /* ?>
            <div class="col-md-3">
                <ul class="menu-vertical">
                @foreach($menus as $menu)
                    <li role="presentation">
                        @if(empty($menu->url))
                            <a href="@if($menu->tipo==10)api @else quem/{{$menu->id}}/{{clean($menu->titulo)}}@endif" accesskey="q"@if($menu->id==$id) class="corrente" @endif style="clear: both;"><i class="fa fa-dot-circle-o" aria-hidden="true"></i> {{$menu->titulo}}</a>
                        @else
                            <a href="{{$menu->url}}">
                                <i class="fa fa-dot-circle-o" aria-hidden="true"></i> {{$menu->titulo}}
                            </a>
                        @endif
                    </li>
                @endforeach
                </ul>
            </div>
            <?php */ ?>
            <div class="col-md-12">
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

