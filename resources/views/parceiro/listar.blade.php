@extends('.layout')
@section('title', trans('links.partners'))
@section('content')
    {{--{{ Counter::count('parceiro') }}--}}
    <div class="container">
        <h1>@lang('links.partners')</h1>
        <div class="line_title bg-pri"></div>
        <br><br>

        @foreach($parceiros as $parceiro)
            <div class="col-md-3">
                <a href="{{$parceiro->url}}" aria-label="{{$parceiro->titulo}}" target="_blank">
                    <picture>
                        <source srcset="imagens/parceiros/sm-{{$parceiro->imagem}}" media="(max-width: 468px)">
                        <source srcset="imagens/parceiros/sm-{{$parceiro->imagem}}" media="(max-width: 768px)">
                        <source srcset="imagens/parceiros/sm-{{$parceiro->imagem}}" class="img-responsive">
                        <img srcset="imagens/parceiros/sm-{{$parceiro->imagem}}" alt="Imagem sobre {{$parceiro->titulo}}" title="Imagem sobre {{$parceiro->titulo}}" width="100%">
                    </picture>
                </a>
            </div>
        @endforeach

    </div>
@endsection