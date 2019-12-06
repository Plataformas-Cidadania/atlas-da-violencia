@extends('layout')
{{--@section('title', trans('links.about'))--}}
@section('content')
    @if(!empty($indicador))
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
            <script>
                var countIndicadores = {{count($indicadores)}};
                function selectIndicador(value){
                    for(let i=0;i<countIndicadores;i++){
                        $('#aba-'+i).hide();
                    }
                    $('#'+value).show();
                }
            </script>
            <div class="col-md-12">
                <select class="form-control fa" name="menuIndicador" id="menuIndicador" onchange="selectIndicador(this.value)">
                    @foreach($indicadores  as $key => $menuIndicador)
                        <option value="aba-{{$key}}">
                            &#xf192; {{$menuIndicador->titulo}}
                        </option>
                    @endforeach
                </select>
            </div>


        </div>
    </div>
    <br>
        <div class="container-fluid">
            <div class="col-md-12">
                @foreach($indicadores as $key => $indicador)
                    <div @if($key>0) style="display:none;" @endif id="aba-{{$key}}">
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
    @endif
@endsection

