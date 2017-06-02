@extends('.layout')
@section('title', trans('links.articles'))
@section('content')
    <script>
        function MM_jumpMenu(targ,selObj,restore){ //v3.0
            eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
            if (restore) selObj.selectedIndex=0;
        }
    </script>

    {{--{{ Counter::count('artigo') }}--}}
    <div class="container">
        <h2>@lang('links.articles')</h2>
        <div class="line_title bg-pri"></div>
        <div class="row">
            <br>


            <div class="col-md-offset-9 col-md-3 text-right">
                <form class="form-inline" action="busca-artigos/{{$origem_id}}/lista" method="post">
                    {!! csrf_field() !!}
                    <div class="form-group">
                        <label class="sr-only" for="exampleInputAmount">Busca</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="busca" name="busca" placeholder="@lang('forms.search')">
                            <div class="input-group-addon">
                                <button type="submit" value="busca-artigos" style="border: 0; background-color: inherit;"><i class="fa fa-search" aria-hidden="true"></i></button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3 col-sm-3">

                <br>


                <ul class="menu-vertical ">
                    @foreach($menus as $menu)
                        <li role="presentation">
                            <a href="artigos/{{$menu->id}}/{{clean($menu->titulo)}}" accesskey="q" @if($menu->id == $origem_id) class="menu-vertical-marcado" @endif  style="clear: both;">
                                <i class="fa fa-dot-circle-o" aria-hidden="true"></i>
                                {{$menu->titulo}}

                                <?php
                                $menuQtd = DB::table('artigos')
                                    ->where('origem_id', $menu->id)
                                    ->count();
                                ;?>
                                <span style="float: right;">({{$menuQtd}})</span>
                            </a></li>
                    @endforeach

                </ul>
                @if($origem_id>0)
                <div class="text-right">
                    <a href="artigos/0/todos/@if($autor_id>0){{$autor_id}}/{{$autor_titulo}} @endif" class="text-danger" > <i class="fa fa-times" aria-hidden="true"></i> Remover filtro</a>
                </div>
                @endif

                <br>
                <h3><i class="fa fa-users" aria-hidden="true"></i> @lang('pages.authors')</h3>
                <hr>
                <ul class="menu-vertical">
                    @foreach($authors as $author)
                        <li>
                                {{$valorBusca}}
                                <?php if($valorBusca!=0){?>
                                    <a href="artigos/0/todos/{{$author->id}}/{{clean($author->titulo)}}" @if($author->id == $autor_id) class="menu-vertical-marcado" @endif>
                                <?php }else{?>
                                    <a href="artigos/{{$origem_id}}/{{$origem_titulo}}/{{$author->id}}/{{clean($author->titulo)}}" @if($author->id == $autor_id) class="menu-vertical-marcado" @endif>
                                <?php }?>

                                <i class="fa fa-pencil-square" aria-hidden="true"></i>
                                {{$author->titulo}}
                                <?php
                                    $authorQtd = DB::table('author_artigo')
                                        ->where('author_id', $author->id)
                                        ->count();
                                ;?>
                                <span style="float: right;">({{$authorQtd}})</span>

                            </a>
                        </li>
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
                                <p>{{str_limit(strip_tags($artigo->descricao), 450)}}</p>
                                <button class="btn btn-none">@lang('buttons.keep-reading')  </button>
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