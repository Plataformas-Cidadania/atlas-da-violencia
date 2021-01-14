@extends('.layout')
@section('title', $publicacao->titulo)
@section('content')

    <div class="container">
        <h1>{{$publicacao->titulo}}</h1>
        <div class="line_title bg-pri"></div>


            <br>
            <div class="row">

                    @if(!empty($publicacao->imagem))
                    <a href="arquivos/artigos/{{$publicacao->arquivo}}" target="_blank">
                    <div class="col-md-5 col-sm-5">
                        <picture>
                            <source srcset="imagens/artigos/sm-{{$publicacao->imagem}}" media="(max-width: 468px)">
                            <source srcset="imagens/artigos/md-{{$publicacao->imagem}}" media="(max-width: 768px)">
                            <source srcset="imagens/artigos/lg-{{$publicacao->imagem}}" class="img-responsive">
                            <img srcset="imagens/artigos/lg-{{$publicacao->imagem}}" alt="Imagem sobre {{$publicacao->titulo}}," title="Imagem sobre {{$publicacao->titulo}}," class="align-img" width="100%">
                        </picture>
                    </div>
                    </a>
                    @endif

                    @if(!empty($publicacao->imagem))<div class="col-md-7 col-sm-7">@else<div class="col-md-12 col-sm-12">@endif
                        <h2></h2>
                        <p>{!! $publicacao->descricao !!}</p>
                        <br><br>
                        <a href="arquivos/artigos/{{$publicacao->arquivo}}" target="_blank">
                            <button class="btn btn-primary">Leia relatório completo</button>
                        </a>
                                <br><br>

                        <?php /* ?>
                        <div class="bg-qui" style="padding: 20px;">
                            <p><strong>Autores</strong></p>
                            <ul class="li-tx">
                                @foreach($authors as $author)
                                    <li>{{$author->titulo}}</li>
                                @endforeach
                            </ul>
                        </div>
                        <?php */ ?>
                    </div>

            </div>



        <div class="row">
            <div class="col-md-12 text-center">
                <h2>Publicações Anteriores</h2>
                <hr>
                <br><br>
            </div>
            <br>
            @foreach($publicacoes as $publicacao)
                <div class="col-md-3 col-sm-3" style="height: 450px;">
                    <a href="publicacoes/{{$publicacao->id}}/{{clean($publicacao->titulo)}}">
                    @if(!empty($publicacao->imagem))
                        <picture>
                            <source srcset="imagens/artigos/sm-{{$publicacao->imagem}}" media="(max-width: 468px)">
                            <source srcset="imagens/artigos/md-{{$publicacao->imagem}}" media="(max-width: 768px)">
                            <source srcset="imagens/artigos/lg-{{$publicacao->imagem}}" class="img-responsive">
                            <img srcset="imagens/artigos/lg-{{$publicacao->imagem}}" alt="Imagem sobre {{$publicacao->titulo}}," title="Imagem sobre {{$publicacao->titulo}}," class="align-img" width="100%">
                        </picture>
                    @endif
                    <br>
                    <p><strong>{{$publicacao->titulo}}</strong></p>
                </a>
                </div>
            @endforeach

        </div>
        <hr>

        <div>{{ $publicacoes->links() }}</div>

    </div>
    <style>
        .li-tx{
            margin: 0;
            padding: 0;
        }
        .li-tx li{
            margin: 0;
            padding: 0;
            font-size: 14px;
            line-height: 18px;
            list-style: none;
        }
    </style>
@endsection

