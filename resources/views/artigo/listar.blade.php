@extends('.layout')
@section('title', 'Artigos')
@section('content')
    <script>
        function MM_jumpMenu(targ,selObj,restore){ //v3.0
            eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
            if (restore) selObj.selectedIndex=0;
        }
    </script>

    {{--{{ Counter::count('artigo') }}--}}
    <div class="container">
        <h2>Artigos</h2>
        <div class="line_title bg-pri"></div>
        <div class="row">
            <br>

            <div class="col-md-offset-6 col-md-3 text-right">
                <select class="form-control" onchange="MM_jumpMenu('parent',this,0)">
                    <option value="">Todos</option>
                    @foreach($authors as $author)
                        <option value="artigos/{{$origem_id}}/{{$origem_titulo}}/{{$author->id}}/{{clean($author->titulo)}}">{{$author->titulo}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 text-right">
                <form class="form-inline" action="/busca-artigos/{{$origem_id}}/lista" method="post">
                    {!! csrf_field() !!}
                    <div class="form-group">
                        <label class="sr-only" for="exampleInputAmount">Busca</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="busca" name="busca" placeholder="Busca">
                            <div class="input-group-addon">
                                <i class="fa fa-search" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3 col-sm-3">
                <br>
                <ul class="menu-vertical">
                    @foreach($menus as $menu)
                        <li role="presentation"><a href="artigos/{{$menu->id}}/{{clean($menu->titulo)}}" accesskey="q" {{--@if($rota=='quem/'.$menu->id.'/'.clean($menu->titulo)) class="corrente" @endif--}} style="clear: both;"><i class="fa fa-dot-circle-o" aria-hidden="true"></i> {{$menu->titulo}}</a></li>
                    @endforeach
                </ul>
            </div>
            <div class="col-md-9 col-sm-9">
        @foreach($artigos as $artigo)
            <div class="row">
                <a href="artigo/{{$artigo->id}}/{{clean($artigo->titulo)}}">
                        @if(!empty($artigo->imagem))
                            <div class="col-md-3 col-sm-3">
                                <picture>
                                    <source srcset="imagens/artigos/sm-{{$artigo->imagem}}" media="(max-width: 468px)">
                                    <source srcset="imagens/artigos/md-{{$artigo->imagem}}" media="(max-width: 768px)">
                                    <source srcset="imagens/artigos/sm-{{$artigo->imagem}}" class="img-responsive">
                                    <img srcset="imagens/artigos/sm-{{$artigo->imagem}}" alt="Imagem sobre {{$artigo->titulo}}," title="Imagem sobre {{$artigo->titulo}}," class="align-img" width="100%">
                                </picture>
                            </div>
                        @endif

                        @if(!empty($artigo->imagem))<div class="col-md-9 col-sm-9">@else<div class="col-md-12 col-sm-12">@endif
                                <h2>{{$artigo->titulo}}</h2>
                                <p>{{str_limit(strip_tags($artigo->descricao), 180)}}</p>
                                <button class="btn btn-none">Continue lendo a notícia</button>
                            </div>
                    </a>
            </div>
            <hr>
        @endforeach

        <div>{{ $artigos->links() }}</div>

        </div>
        </div>

    </div>
@endsection