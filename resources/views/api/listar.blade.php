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
                        </style>
                        </head>
                        <body>



                        <div class="accordion"> <button class="btn btn-primary">GET</button> /api/v1/fontes/id <span style="float: right; margin: 5px 5px 0 0;">Busca lista das fontes. </span></div>
                        <div class="panel">
                            <h2>Notas de Implementação</h2>
                            <p>Obtém listas das fontes. Veja notas sobre parâmetros individuais abaixo.</p>
                            <pre>
                                {
  "code": "int",
  "status": "string",
  "copyright": "string",
  "attributionText": "string",
  "attributionHTML": "string",
  "data": {
    "offset": "int",
    "limit": "int",
    "total": "int",
    "count": "int",
    "results": [
      {
        "id": "int",
        "name": "string",
        "description": "string",
        "modified": "Date",
        "resourceURI": "string",
        "urls": [
          {
            "type": "string",
            "url": "string"
          }
        ],
        "series": {
          "available": "int",
          "returned": "int",
          "collectionURI": "string",
          "items": [
            {
              "resourceURI": "string",
              "name": "string"
            }
          ]
        }
      }
    ]
  },
  "etag": "string"
}
                            </pre>
                        </div>

                        <div class="accordion">Section 2</div>
                        <div class="panel">
                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                        </div>

                        <div class="accordion">Section 3</div>
                        <div class="panel">
                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                        </div>

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

            </div>
        </div>
    </div>
@endsection

