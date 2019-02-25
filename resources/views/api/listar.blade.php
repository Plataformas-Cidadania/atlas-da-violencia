@extends('layout')
@section('title', trans('links.about'))
@section('content')

    <div class="container">
        <h2 id="calendar" aria-label="@lang('links.about')">{{$textoApi->titulo}}</h2>
        <div class="line_title bg-pri"></div>
        <br>
        <div class="row">

            <div class="col-md-12">

                    @if(!empty($textoApi->imagem))
                        <picture>
                            <source srcset="imagens/quemsomos/sm-{{$textoApi->imagem}}" media="(max-width: 468px)">
                            <source srcset="imagens/quemsomos/md-{{$textoApi->imagem}}" media="(max-width: 768px)">
                            <source srcset="imagens/quemsomos/md-{{$textoApi->imagem}}" class="img-responsive">
                            <img srcset="imagens/quemsomos/md-{{$textoApi->imagem}}" alt="Imagem sobre, {{$textoApi->titulo}}" title="Imagem sobre, {{$textoApi->titulo}}" class="align-img">
                        </picture>
                    @endif
                    <p ng-class="{'alto-contraste': altoContrasteAtivo}">{!!$textoApi->descricao!!}</p>

                        <style>
                            .accordion {
                                background-color: #eee;
                                color: #444;
                                cursor: pointer;
                                padding: 0px;
                                width: 100%;
                                border: none;
                                text-align: left;
                                outline: none;
                                font-size: 15px;
                                transition: 0.4s;
                                margin-bottom: 5px;
                            }

                            .active, .accordion:hover {
                                background-color: #ccc;
                            }

                            .panel {
                                padding: 0 18px;
                                display: none;
                                background-color: white;
                                overflow: hidden;
                            }
                            .table-api table{
                                width: 100%;
                            }
                            .table-api td{
                                border: solid 1px #CCCCCC;
                                padding: 5px;
                            }
                        </style>
                        </head>
                        <body>


                        @foreach($apis as $api)
                        <div class="accordion">
                            <button class="btn btn-primary">GET</button> {{$api->url}}<span style="float: right; margin: 5px 5px 0 0;">{{$api->titulo}} </span>
                        </div>
                        <div class="panel">
                            <div style="float: right;">
                                <p>
                                    <textarea class="js-copytextarea" style="position: absolute; margin-top: -50000px;">{{$api->url}}</textarea>
                                <p class="js-textareacopybtn btn btn-primary" style="cursor: pointer;"><i class="fa fa-clone fa"></i> Copiar rota</p>
                                </p>
                            </div>
                            <br>
                            @if(!empty($api->descricao))
                            <h4>Parâmetros</h4>
                            @endif
                            <div class="table-api">{!! $api->descricao !!}</div>
                            <br> <br>
                            <h4>Resposta</h4>
                            {!! $api->resposta !!}

                        </div>
                        @endforeach

                        {{--<div class="accordion">Section 2</div>
                        <div class="panel">
                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                        </div>

                        <div class="accordion">Section 3</div>
                        <div class="panel">
                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                        </div>--}}

                        <script>
                            var acc = document.getElementsByClassName("accordion");
                            var i;

                            for (i = 0; i < acc.length; i++) {
                                acc[i].addEventListener("click", function() {
                                    this.classList.toggle("active");
                                    var panel = this.nextElementSibling;
                                    if (panel.style.display === "block") {
                                        panel.style.display = "none";
                                    } else {
                                        panel.style.display = "block";
                                    }
                                });
                            }
                        </script>

                        <script>
                            window.onload = function() {
                                // Pega todos os elementos correspondentes
                                var copyTextareaBtn = Array.prototype.slice.
                                call(document.querySelectorAll('.js-textareacopybtn'));
                                var copyTextarea = Array.prototype.slice.
                                call(document.querySelectorAll('.js-copytextarea'));

                                // Laço para percorrer todos os elementos
                                copyTextareaBtn.forEach(function(btn, idx) {
                                    btn.addEventListener("click", function() {
                                        // Copia o conteudo do textarea
                                        copyTextarea[idx].select();
                                        var msg = document.execCommand('copy')
                                            ? 'funcionou' : 'deu erro';
                                        console.log('Compando para copiar texto ' + msg);
                                    });

                                });
                            }
                        </script>

            </div>
        </div>
    </div>
@endsection

