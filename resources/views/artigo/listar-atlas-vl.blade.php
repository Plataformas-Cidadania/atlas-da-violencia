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
        let strAutores = "<?php echo $autores;?>";
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

        function encode_utf8(s) {
            return unescape(encodeURIComponent(s));
        }

        function decode_utf8(s) {
            return decodeURIComponent(escape(s));
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

        function submitFormFromMenu(assunto_id){
            console.log(assunto_id);
            document.getElementById('assunto_id').value = assunto_id;
            document.getElementById('frmBusca').submit();
        }

        function submitForm(){
            if(document.getElementById('autorName').value===""){
                document.getElementById('autorId').value = 0;
            }
        }

        function loadMore(take){
            //console.log(take-10);
            document.getElementById('take').value = take;
            document.getElementById('frmBusca').action = "busca-artigos-v2#artigo_"+(take-10);
            document.getElementById('frmBusca').submit();
        }

    </script>
    <style>
        fieldset.border {
            border: solid 1px #ddd !important;
            padding: 0 1.4em 1.4em 1.4em !important;
            margin: 0 0 1.5em 0 !important;
        }

        legend.border {
            color: #505050;
            font-size: 1.2em !important;
            font-weight: bold !important;
            text-align: left !important;
            width:auto;
            padding:0 10px;
            border-bottom:none;
        }
    </style>

    {{--{{ Counter::count('artigo') }}--}}
    <div class="container">
        <div class="row">
            <div class="col-md-12">

                <h1>@lang('links.articles2')</h1>
                <div class="line_title bg-pri"></div>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-12">
                <fieldset class="border">
                    <legend class="border">Busca</legend>
                    <form class="form" name="frmBusca" id="frmBusca" action="busca-artigos-v2" onsubmit="return submitForm()" method="post">
                        <input type="hidden" name="assunto_id" id="assunto_id" value="{{$assunto_id}}">
                        <input type="hidden" name="take" id="take" value="{{$take}}">
                        {!! csrf_field() !!}
                        <div class="row">
                            <div class="col-md-3">
                                <label for="busca">Título</label>
                                <input type="text" class="form-control" id="busca" name="busca" value="{{$tituloBusca}}" placeholder="Digite uma palavra do título">
                            </div>
                            <div class="col-md-3">
                                <label for="ano">Autor</label>
                                <input type="text" class="form-control" name="autorName" id="autorName"  value="{{$autorNomeBusca}}" onkeyup="searchAutores(this.value)">
                                <input type="hidden" name="autorId" id="autorId" value="{{$autorIdBusca}}">
                                <div class="div-info" id="divAutores" style="display: none;"></div>
                            </div>
                            <div class="col-md-2">
                                <label for="ano">Ano</label>
                                <select name="ano" id="ano" class="form-control">
                                    <option value="0">Todos</option>
                                    @foreach($anos as $ano)
                                        <option value="{{$ano}}" @if($ano==$anoBusca)selected="selected"@endif>{{$ano}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <input type="hidden" id="publicacaoAtlas" name="publicacaoAtlas" value="{{$publicacaoAtlasBusca}}">
                            {{--<div class="col-md-2">
                                <br>
                                <label for="publicacaoAtlas">
                                    <input type="checkbox" id="publicacaoAtlas" name="publicacaoAtlas" value="1" @if($publicacaoAtlasBusca==1) checked @endif
                                           style="width: 20px; height: 20px; margin: 0 10px 0 0; top: 15px; position: relative; float: left;">
                                    <div style="float: left; padding-top: 15px;">Atlas Violência</div>
                                </label>
                            </div>--}}

                            <div class="col-md-1">
                                <button type="text" class="btn btn-info" onClick="searchArticles()" style="margin: 25px 0 0 0;">Pesquisar</button>
                            </div>
                        </div>
                    </form>
                </fieldset>
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
                <h4 style="margin-left: 4px;">Assuntos</h4>
                <ul class="menu-vertical ">
                    @foreach($menus as $menu)
                        <li role="presentation">
                            <?php /* ?><a href="artigos-v2/{{$menu->id}}/{{clean($menu->titulo)}}/{{$anoBusca}}/{{$autorIdBusca}}/{{$autorNomeBusca}}/{{$publicacaoAtlasBusca}}/" accesskey="q" @if($menu->id == $origem_id) class="menu-vertical-marcado" @endif  style="clear: both;"><?php */?>
                            <a onClick="submitFormFromMenu({{$menu->id}})" accesskey="q" @if($menu->id == $assunto_id) class="menu-vertical-marcado" @endif  style="cursor:pointer; clear: both;">
                                <i class="fa fa-dot-circle-o" aria-hidden="true"></i>
                                {{$menu->titulo}}
                                <span style="float: right;">({{$menu->qtd}})</span>
                            </a></li>
                    @endforeach
                </ul>
                @if($assunto_id>0)
                <div class="text-right">
                    <a  class="text-danger" onclick="submitFormFromMenu(0)" style="cursor: pointer;"> <i class="fa fa-times" aria-hidden="true"></i> Remover filtro</a>
                    <?php /* ?><a href="artigos/0/todos @if($autor_id>0)/{{$autor_id}}/{{$autor_titulo}} @endif" class="text-danger" > <i class="fa fa-times" aria-hidden="true"></i> Remover filtro</a><?php */?>
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
        @foreach($artigos as $index => $artigo)
            <div class="row" id="artigo_{{$index}}">
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

                            <div  @if(!empty($artigo->imagem))class="col-md-9 col-sm-9 descricao-publicaco" @else class="col-md-12 col-sm-12 descricao-publicaco" @endif >
                                <h2>{{$artigo->titulo}}</h2>
                                <p>{{str_limit(strip_tags($artigo->descricao), 450)}}</p>
                                <button class="btn btn-none">@lang('buttons.keep-reading')  </button>
                            </div>
                    </a>
            </div>
            <hr>
        @endforeach

        {{--<div>{{ $artigos->links() }}</div>--}}
        @if(count($artigos) < $totalArtigos-1)
        <div class="text-center">
            <button class="btn btn-info" onclick="loadMore({{$take+1}})">
                Veja Mais Publicações
            </button>
        </div>
        @endif

        </div>
        </div>

    </div>
@endsection
