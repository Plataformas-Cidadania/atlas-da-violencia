<?php

    $anos = [];
    $autores = "";
    $fontesUsadas = [];

    foreach ($artigos as $artigo) {
        $ano = date('Y', strtotime($artigo->created_at));
        if($artigo->data){
            $ano = date('Y', strtotime($artigo->data));
        }
        if(!in_array($ano, $anos)){
            array_push($anos, $ano);
        }
    }

    foreach ($authors as $index => $author) {
        $autores .= $author->id.'__'.$author->titulo.',';
    }
    $autores = substr($autores, 0, -1);

?>

@extends('.layout')
@section('title', trans('links.articles'))
@section('content')
    <script>
        function MM_jumpMenu(targ,selObj,restore){ //v3.0
            eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
            if (restore) selObj.selectedIndex=0;
        }

        let strAutores = "{{$autores}}";
        let arrayAutores = strAutores.split(",");
        let autores = [];
        arrayAutores.find((item)=>{
            autor = item.split("__");
            id = autor[0];
            nome = autor[1];
            if(!autores.some(el => el.id === id)){
                autores.push({id: id,  nome: nome});
            }
        });
        console.log(autores);


        function searchAutores(search){
            document.getElementById("divAutores").style.display = "block";
            let lista = autores.filter((item) => item.nome.toLowerCase().includes(search.toLowerCase()));
            console.log(lista);
            listAutores(lista);
            //return item.indexOf(search) === -1;
        }

        function setAutor(id, nome){
            document.getElementById("divAutores").style.display = "none";
            document.getElementById("autorId").value = id;
            document.getElementById("autorName").value = nome;
        }

        function listAutores(autores){
            limparAutores();
            let divAutores = document.getElementById("divAutores");
            for(let i in autores){
                let divAutor = document.createElement('div');
                let text = document.createTextNode(autores[i].nome);
                divAutor.appendChild(text);
                divAutor.setAttribute("onClick", "setAutor("+autores[i].id+",'"+autores[i].nome+"')")
                divAutor.style.cursor = "pointer";
                divAutores.appendChild(divAutor);
            }
        }

        function limparAutores(){
            let divAutores = document.getElementById("divAutores");
            while (divAutores.firstChild) {
                divAutores.removeChild(divAutores.firstChild);
            }
        }

        function submitFormFromMenu(origem_id){
            document.getElementById('origem_id').value = origem_id;
            document.getElementById('frmBusca').onsubmit;
        }

    </script>

    {{--{{ Counter::count('artigo') }}--}}
    <div class="container">
        <h1>@lang('links.articles2')</h1>
        <div class="line_title bg-pri"></div>

        <br>
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <form class="form" name="frmBusca" id="frmBusca" action="busca-artigos-v2" method="post">
                    <input type="hidden" name="origem_id" id="origem_id" value="{{$origem_id}}">
                    {!! csrf_field() !!}
                    <div class="row">
                        <div class="col-md-2">
                            <label for="ano">Ano</label>
                            <select name="ano" id="ano" class="form-control">
                                <option value="0">Todos</option>
                                @foreach($anos as $ano)
                                    <option value="{{$ano}}" @if($ano==$anoBusca)selected="selected"@endif>{{$ano}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="ano">Autor</label>
                            <input type="text" class="form-control" name="autorName" id="autorName"  value="{{$autorNomeBusca}}" onkeyup="searchAutores(this.value)">
                            <input type="hidden" name="autorId" id="autorId" value="{{$autorIdBusca}}">
                            <div class="div-info" id="divAutores" style="display: none;"></div>
                        </div>
                        <div class="col-md-6">
                            <br>
                            <label for="publicacaoAtlas">
                                <input type="checkbox" id="publicacaoAtlas" name="publicacaoAtlas" value="1" @if($publicacaoAtlasBusca==1) checked @endif
                                       style="width: 20px; height: 20px; margin: 0 10px 0 0; top: 15px; position: relative; float: left;">
                                <div style="float: left; padding-top: 15px;">Atlas Violência</div>
                            </label>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-8">
                            <label for="busca">Título</label>
                            <input type="text" class="form-control" id="busca" name="busca" value="{{$tituloBusca}}">
                        </div>
                        <div class="col-md-2">
                            <button type="text" class="btn btn-info" onClick="searchArticles()" style="margin: 25px 0 0 5px;">Pesquisar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>


        <div class="row">
            <br>


            {{--<div class="col-md-offset-9 col-md-3 text-right">
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
            </div>--}}
        </div>
        <div class="row">
            <div class="col-md-3 col-sm-3">

                <br>


                <ul class="menu-vertical ">
                    @foreach($menus as $menu)
                        <li role="presentation">
                            <?php /* ?><a href="artigos-v2/{{$menu->id}}/{{clean($menu->titulo)}}/{{$anoBusca}}/{{$autorIdBusca}}/{{$autorNomeBusca}}/{{$publicacaoAtlasBusca}}/" accesskey="q" @if($menu->id == $origem_id) class="menu-vertical-marcado" @endif  style="clear: both;"><?php */?>
                            <a onClick="submitFormFromMenu({{$menu->id}})" accesskey="q" @if($menu->id == $origem_id) class="menu-vertical-marcado" @endif  style="clear: both;">
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
                    <a href="artigos/0/todos @if($autor_id>0)/{{$autor_id}}/{{$autor_titulo}} @endif" class="text-danger" > <i class="fa fa-times" aria-hidden="true"></i> Remover filtro</a>
                </div>
                @endif

                <br>
                <?php /*
                <h3><i class="fa fa-users" aria-hidden="true"></i> @lang('pages.authors')</h3>
                <hr>
                <ul class="menu-vertical">
                    @foreach($authors as $author)
                        <li>

                                <?php if($valorBusca!='0'){?>
                                    <a href="artigos/0/todos/{{$author->id}}/{{clean($author->titulo)}}" @if($author->id == $autor_id) class="menu-vertical-marcado" @endif>
                                <?php }else{?>
                                    <a href="artigos/{{$origem_id}}/{{$origem_titulo}}/{{$author->id}}/{{clean($author->titulo)}}" @if($author->id == $autor_id) class="menu-vertical-marcado" @endif>
                                <?php }?>

                                <i class="fa fa-pencil-square" aria-hidden="true"></i>
                                {{$author->titulo}}
                                <?php
				    $lang =  App::getLocale();

                                    $authorQtd = DB::table('author_artigo')
					->join('artigos', 'artigos.id', '=', 'author_artigo.artigo_id')
                                        ->where('author_id', $author->id)
					->where('artigos.idioma_sigla', $lang)
                                        ->count();
                                ;?>
                                <span style="float: right;">({{$authorQtd}})</span>

                            </a>
                        </li>
                    @endforeach
                </ul>
                */ ?>

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
