@extends('layout')
{{--@section('title', trans('links.about'))--}}
@section('content')

    <div class="container">
        <h2 id="calendar" aria-label="{{--@lang('links.about')--}}">{{--@lang('links.about')--}}</h2>
        <div class="line_title bg-pri"></div>
        <br>
        <div class="row">
            <div class="col-md-12">
                @if(!empty($indicador->imagem))
                    <picture>
                        <source srcset="imagens/indicadorsomos/sm-{{$indicador->imagem}}" media="(max-width: 468px)">
                        <source srcset="imagens/indicadorsomos/md-{{$indicador->imagem}}" media="(max-width: 768px)">
                        <source srcset="imagens/indicadorsomos/md-{{$indicador->imagem}}" class="img-responsive">
                        <img srcset="imagens/indicadorsomos/md-{{$indicador->imagem}}" alt="Imagem sobre, {{$indicador->titulo}}" title="Imagem sobre, {{$indicador->titulo}}" class="align-img">
                    </picture>
                @endif
                <p ng-class="{'alto-contraste': altoContrasteAtivo}">{!!$indicador->descricao!!}</p>
                    <br><br>
            </div>
            <div class="col-md-4">
                <?php $id = 1;?>
                    <ul class="menu-vertical">
                        @foreach($menuIndicadores  as $key => $menuIndicador)
                            <li role="presentation" @if($key==0) active @endif><a href="#aba-{{$key}}"  aria-controls="aba-{{$key}}" role="tab" data-toggle="tab" style="clear: both;"><i class="fa fa-dot-circle-o" aria-hidden="true"></i> {{$menuIndicador->titulo}}</a></li>
                        @endforeach
                    </ul>
            </div>
            <div class="col-md-8">

                <div id="myTabContent" class="tab-content">

                @foreach($indicadores  as $key => $indicador)
                    <div role="tabpanel" class="tab-pane fade in @if($key==0) active @endif" id="aba-{{$key}}" aria-labelledby="home-tab">
                        <iframe
                                @if($indicador->url)
                                    src="{{$indicador->url}}"
                                @else
                                    src="arquivos/rmd/{{$indicador->arquivo}}"
                                @endif
                                frameborder="0" width="100%" height="1200">

                        </iframe>
                    </div>
                @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection

