@extends('layout')
@section('title', trans('links.about'))
@section('content')

    <div class="container">
        <h2 id="calendar" aria-label="@lang('links.about')">@lang('links.about')</h2>
        <div class="line_title bg-pri"></div>
        <br>
        <div class="row">
            <div class="col-md-3">

            <ul class="menu-vertical">
            @foreach($menus as $menu)
                <li role="presentation"><a href="quem/{{$menu->id}}/{{clean($menu->titulo)}}" accesskey="q"@if($menu->id==$id) class="corrente" @endif style="clear: both;"><i class="fa fa-dot-circle-o" aria-hidden="true"></i> {{$menu->titulo}}</a></li>
            @endforeach
            </ul>

            </div>
            <div class="col-md-9">
                @if($quem->id!='9')
                    @if(!empty($quem->imagem))
                        <picture>
                            <source srcset="imagens/quemsomos/sm-{{$quem->imagem}}" media="(max-width: 468px)">
                            <source srcset="imagens/quemsomos/md-{{$quem->imagem}}" media="(max-width: 768px)">
                            <source srcset="imagens/quemsomos/md-{{$quem->imagem}}" class="img-responsive">
                            <img srcset="imagens/quemsomos/md-{{$quem->imagem}}" alt="Imagem sobre, {{$quem->titulo}}" title="Imagem sobre, {{$quem->titulo}}" class="align-img">
                        </picture>
                    @endif
                        <p ng-class="{'alto-contraste': altoContrasteAtivo}">{!!$quem->descricao!!}</p>
                @else
                    <div class="bs-callout bs-callout-warning">
                        <p ng-class="{'alto-contraste': altoContrasteAtivo}">{!!$quem->descricao!!}</p>
                    </div>
                    <br>
                    <div>
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="active"><a href="#diretivas" aria-controls="home" role="tab" data-toggle="tab">Diretivas</a></li>
                            <li role="presentation"><a href="#horizontal" aria-controls="horizontal" role="tab" data-toggle="tab">Versões horizontais</a></li>
                            <li role="presentation"><a href="#vertical" aria-controls="vertical" role="tab" data-toggle="tab">Versões verticais</a></li>
                            <li role="presentation"><a href="#impressao" aria-controls="impressao" role="tab" data-toggle="tab">Impressão em altaresolução</a></li>
                            @if($printingsManual)<li role="presentation"><a href="#manual" aria-controls="manual" role="tab" data-toggle="tab">Manual</a></li>@endif
                        </ul>
                        <!-- Tab panes -->
                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane active" id="diretivas">
                                {{--///////////////--}}
                                <br><br>
                                <?php $cont1=0;?>
                                <?php $cont2 = 0;?>
                                @foreach($directivesType as $directiveType)
                                    <?php

                                    switch ($directiveType->type) {
                                        case 1:
                                            $valor_cont = 'Versões da Marca';
                                            break;
                                        case 2:
                                            $valor_cont = 'Reduções mínimas';
                                            break;
                                        case 3:
                                            $valor_cont = 'Usando com outras marcas';
                                            break;
                                        case 4:
                                            $valor_cont = 'Utilização em aplicações';
                                            break;
                                        case 5:
                                            $valor_cont = 'Erros comuns';
                                            break;
                                    }
                                    ?>
                                <style>
                                    .btn-dd{
                                        float: right;
                                        cursor: pointer;
                                        margin: 10px;
                                    }
                                </style>

                                        <div class="bg-qui">
                                            <p style="padding:15px 0 0 10px; float: left;">{{$valor_cont}}</p>
                                            <i class="fa fa-minus-square fa-2x btn-dd"  style=" @if($cont1>0) display: none; @else display: block; @endif " id="minus-<?php echo $cont1;?>" aria-hidden="true"  onclick="minusDd('<?php echo $cont1;?>')"></i>
                                            <i class="fa fa-plus-square fa-2x btn-dd" style=" @if($cont1>0) display: block; @else display: none; @endif " aria-hidden="true" id="plus-<?php echo $cont1;?>"  onclick="plusDd('<?php echo $cont1;?>')"></i>
                                            <div style="clear: both;"></div>
                                        </div>

                                        <script>
                                            function minusDd(id){
                                                document.getElementById('tabela-'+id).style = "display: none";
                                                document.getElementById('minus-'+id).style = "display: none";
                                                document.getElementById('plus-'+id).style = "display: block";
                                            }
                                            function plusDd(id){
                                                document.getElementById('tabela-'+id).style = "display: block";
                                                document.getElementById('minus-'+id).style = "display: block";
                                                document.getElementById('plus-'+id).style = "display: none";
                                            }
                                        </script>
                                    <?php $directives = DB::table('directives')->where('type', $directiveType->type)->get();?>

                                        <div class="row" id="tabela-{{$cont1}}" style=" @if($cont1>0) display: none; @else display: block; @endif ">
                                            @foreach($directives as $directive)
                                                <br>
                                               <br>

                                                    <div class="col-md-3" style="border-right: solid 1px #EEEEEE; height: 100%; "><p>{!! $directive->description !!}</p></div>
                                                    <div class="col-md-2 text-center"><h4 style="vertical-align: middle;">{{$directive->title}}</h4></div>
                                                    <div class="col-md-7" style="border-left: solid 1px #EEEEEE;"><img srcset="imagens/directives/md-{{$directive->imagem}}" alt="Imagem sobre, {{$directive->title}}" title="Imagem sobre, {{$directive->title}}" style="width: 100%;"></div>
                                                    <hr>

                                                <br>
                                                <?php $cont2++;?>
                                            @endforeach
                                        </div>


                                        <?php $cont1++;?>
                                @endforeach



                                {{--///////////////--}}

                            </div>
                            <div role="tabpanel" class="tab-pane" id="horizontal">
                                <div class="text-center">
                                    <br><br><br>

                                    {{--//////////--}}
                                    <div class="row">
                                        <div class="col-md-4 box-marca">
                                            <div><img srcset="imagens/artworks/72-{{$artwork->imagem}}" alt="{{$artwork->title}}" title="{{$artwork->title}}" ></div>
                                            <h5>72px de largura × 28px de altura</h5><br>
                                            <i class="fa fa-download fa-2x" style="color: #3498DB;" aria-hidden="true"></i><br><br>
                                            <p><a href="imagens/artworks/72-{{$artwork->imagem}}" target="_blank">PNG</a> | <a href="imagens/artworks/72-{{$artworkJpg->imagem}}" target="_blank">JPG</a></p>
                                        </div>
                                        <div class="col-md-4 box-marca">
                                            <div><img srcset="imagens/artworks/96-{{$artwork->imagem}}" alt="{{$artwork->title}}" title="{{$artwork->title}}" ></div>
                                            <h5>96px de largura × 37px de altura</h5><br>
                                            <i class="fa fa-download fa-2x" style="color: #3498DB;" aria-hidden="true"></i><br><br>
                                            <p><a href="imagens/artworks/72-{{$artwork->imagem}}" target="_blank">PNG</a> | <a href="imagens/artworks/96-{{$artworkJpg->imagem}}" target="_blank">JPG</a></p>
                                        </div>
                                        <div class="col-md-4 box-marca">
                                            <div><img srcset="imagens/artworks/114-{{$artwork->imagem}}" alt="{{$artwork->title}}" title="{{$artwork->title}}" ></div>
                                            <h5>114px de largura × 44px de altura</h5><br>
                                            <i class="fa fa-download fa-2x" style="color: #3498DB;" aria-hidden="true"></i><br><br>
                                            <p><a href="imagens/artworks/114-{{$artwork->imagem}}" target="_blank">PNG</a> | <a href="imagens/artworks/114-{{$artworkJpg->imagem}}" target="_blank">JPG</a></p>
                                        </div>
                                        <div class="col-md-12">
                                            <br><hr><br>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4 box-marca">
                                            <div><img srcset="imagens/artworks/128-{{$artwork->imagem}}" alt="{{$artwork->title}}" title="{{$artwork->title}}" ></div>
                                            <h5>128px de largura × 50px de altura</h5><br>
                                            <i class="fa fa-download fa-2x" style="color: #3498DB;" aria-hidden="true"></i><br><br>
                                            <p><a href="imagens/artworks/128-{{$artwork->imagem}}" target="_blank">PNG</a> | <a href="imagens/artworks/128-{{$artworkJpg->imagem}}" target="_blank">JPG</a></p>
                                        </div>
                                        <div class="col-md-4 box-marca">
                                            <div><img srcset="imagens/artworks/144-{{$artwork->imagem}}" alt="{{$artwork->title}}" title="{{$artwork->title}}" ></div>
                                            <h5>144px de largura × 56px de altura</h5><br>
                                            <i class="fa fa-download fa-2x" style="color: #3498DB;" aria-hidden="true"></i><br><br>
                                            <p><a href="imagens/artworks/144-{{$artwork->imagem}}" target="_blank">PNG</a> | <a href="imagens/artworks/144-{{$artworkJpg->imagem}}" target="_blank">JPG</a></p>
                                        </div>
                                        <div class="col-md-4 box-marca">
                                            <div><img srcset="imagens/artworks/256-{{$artwork->imagem}}" alt="{{$artwork->title}}" title="{{$artwork->title}}" ></div>
                                            <h5>256px de largura × 100px de altura</h5><br>
                                            <i class="fa fa-download fa-2x" style="color: #3498DB;" aria-hidden="true"></i><br><br>
                                            <p><a href="imagens/artworks/256-{{$artwork->imagem}}" target="_blank">PNG</a> | <a href="imagens/artworks/256-{{$artworkJpg->imagem}}" target="_blank">JPG</a></p>
                                        </div>
                                        <div class="col-md-12">
                                            <br><hr><br>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 box-marca">
                                            <div><img srcset="imagens/artworks/512-{{$artwork->imagem}}" alt="{{$artwork->title}}" title="{{$artwork->title}}" ></div>
                                            <h5>512x de largura × 199px de altura</h5><br>
                                            <i class="fa fa-download fa-2x" style="color: #3498DB;" aria-hidden="true"></i><br><br>
                                            <p><a href="imagens/artworks/512-{{$artwork->imagem}}" target="_blank">PNG</a> | <a href="imagens/artworks/512-{{$artworkJpg->imagem}}" target="_blank">JPG</a></p>
                                        </div>
                                        <div class="col-md-12">
                                            <br><hr><br>
                                        </div>
                                        <div class="col-md-12 box-marca">
                                            <div><img srcset="imagens/artworks/1024-{{$artwork->imagem}}" alt="{{$artwork->title}}" title="{{$artwork->title}}" width="100%"></div>
                                            <h5>1024px de largura × 398px de altura</h5><br>
                                            <i class="fa fa-download fa-2x" style="color: #3498DB;" aria-hidden="true"></i><br><br>
                                            <p><a href="imagens/artworks/1024-{{$artwork->imagem}}" target="_blank">PNG</a> | <a href="imagens/artworks/1024-{{$artworkJpg->imagem}}" target="_blank">JPG</a></p>
                                        </div>
                                        <div class="col-md-12">
                                            <br><hr><br>
                                        </div>
                                        <div class="col-md-12 box-marca">
                                            <div><img srcset="imagens/artworks/2048-{{$artwork->imagem}}" alt="{{$artwork->title}}" title="{{$artwork->title}}" width="100%" ></div>
                                            <h5>2048px de largura × 796px de altura</h5><br>
                                            <i class="fa fa-download fa-2x" style="color: #3498DB;" aria-hidden="true"></i><br><br>
                                            <p><a href="imagens/artworks/2048-{{$artwork->imagem}}" target="_blank">PNG</a> | <a href="imagens/artworks/2048-{{$artworkJpg->imagem}}" target="_blank">JPG</a></p>
                                        </div>
                                    </div>
                                    {{--//////////--}}

                                </div>
                            </div>
                            <div role="tabpanel" class="tab-pane text-center" id="vertical">
                                {{--//////////--}}
                                <br><br><br>
                                <div class="row">
                                    <div class="col-md-4 box-marca">
                                        <div><img srcset="imagens/artworks/72-{{$artworkVert->imagem}}" alt="{{$artworkVert->title}}" title="{{$artworkVert->title}}" ></div>
                                        <h5>72px de largura × 28px de altura</h5><br>
                                        <i class="fa fa-download fa-2x" style="color: #3498DB;" aria-hidden="true"></i><br><br>
                                        <p><a href="imagens/artworks/72-{{$artworkVert->imagem}}" target="_blank">PNG</a> | <a href="imagens/artworks/72-{{$artworkVertJpg->imagem}}" target="_blank">JPG</a></p>
                                    </div>
                                    <div class="col-md-4 box-marca">
                                        <div><img srcset="imagens/artworks/96-{{$artworkVert->imagem}}" alt="{{$artworkVert->title}}" title="{{$artworkVert->title}}" ></div>
                                        <h5>96px de largura × 37px de altura</h5><br>
                                        <i class="fa fa-download fa-2x" style="color: #3498DB;" aria-hidden="true"></i><br><br>
                                        <p><a href="imagens/artworks/72-{{$artworkVert->imagem}}" target="_blank">PNG</a> | <a href="imagens/artworks/96-{{$artworkVertJpg->imagem}}" target="_blank">JPG</a></p>
                                    </div>
                                    <div class="col-md-4 box-marca">
                                        <div><img srcset="imagens/artworks/114-{{$artworkVert->imagem}}" alt="{{$artworkVert->title}}" title="{{$artworkVert->title}}" ></div>
                                        <h5>114px de largura × 44px de altura</h5><br>
                                        <i class="fa fa-download fa-2x" style="color: #3498DB;" aria-hidden="true"></i><br><br>
                                        <p><a href="imagens/artworks/114-{{$artworkVert->imagem}}" target="_blank">PNG</a> | <a href="imagens/artworks/114-{{$artworkVertJpg->imagem}}" target="_blank">JPG</a></p>
                                    </div>
                                    <div class="col-md-12">
                                        <br><hr><br>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 box-marca">
                                        <div><img srcset="imagens/artworks/128-{{$artworkVert->imagem}}" alt="{{$artworkVert->title}}" title="{{$artworkVert->title}}" ></div>
                                        <h5>128px de largura × 50px de altura</h5><br>
                                        <i class="fa fa-download fa-2x" style="color: #3498DB;" aria-hidden="true"></i><br><br>
                                        <p><a href="imagens/artworks/128-{{$artworkVert->imagem}}" target="_blank">PNG</a> | <a href="imagens/artworks/128-{{$artworkVertJpg->imagem}}" target="_blank">JPG</a></p>
                                    </div>
                                    <div class="col-md-4 box-marca">
                                        <div><img srcset="imagens/artworks/144-{{$artworkVert->imagem}}" alt="{{$artworkVert->title}}" title="{{$artworkVert->title}}" ></div>
                                        <h5>144px de largura × 56px de altura</h5><br>
                                        <i class="fa fa-download fa-2x" style="color: #3498DB;" aria-hidden="true"></i><br><br>
                                        <p><a href="imagens/artworks/144-{{$artworkVert->imagem}}" target="_blank">PNG</a> | <a href="imagens/artworks/144-{{$artworkVertJpg->imagem}}" target="_blank">JPG</a></p>
                                    </div>
                                    <div class="col-md-4 box-marca">
                                        <div><img srcset="imagens/artworks/256-{{$artworkVert->imagem}}" alt="{{$artworkVert->title}}" title="{{$artworkVert->title}}" ></div>
                                        <h5>256px de largura × 100px de altura</h5><br>
                                        <i class="fa fa-download fa-2x" style="color: #3498DB;" aria-hidden="true"></i><br><br>
                                        <p><a href="imagens/artworks/256-{{$artworkVert->imagem}}" target="_blank">PNG</a> | <a href="imagens/artworks/256-{{$artworkVertJpg->imagem}}" target="_blank">JPG</a></p>
                                    </div>
                                    <div class="col-md-12">
                                        <br><hr><br>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 box-marca">
                                        <div><img srcset="imagens/artworks/512-{{$artworkVert->imagem}}" alt="{{$artworkVert->title}}" title="{{$artworkVert->title}}" ></div>
                                        <h5>512x de largura × 199px de altura</h5><br>
                                        <i class="fa fa-download fa-2x" style="color: #3498DB;" aria-hidden="true"></i><br><br>
                                        <p><a href="imagens/artworks/512-{{$artworkVert->imagem}}" target="_blank">PNG</a> | <a href="imagens/artworks/512-{{$artworkVertJpg->imagem}}" target="_blank">JPG</a></p>
                                    </div>
                                    <div class="col-md-12">
                                        <br><hr><br>
                                    </div>
                                    <div class="col-md-12 box-marca">
                                        <div><img srcset="imagens/artworks/1024-{{$artworkVert->imagem}}" alt="{{$artworkVert->title}}" title="{{$artworkVert->title}}" width="100%"></div>
                                        <h5>1024px de largura × 398px de altura</h5><br>
                                        <i class="fa fa-download fa-2x" style="color: #3498DB;" aria-hidden="true"></i><br><br>
                                        <p><a href="imagens/artworks/1024-{{$artworkVert->imagem}}" target="_blank">PNG</a> | <a href="imagens/artworks/1024-{{$artworkVertJpg->imagem}}" target="_blank">JPG</a></p>
                                    </div>
                                    <div class="col-md-12">
                                        <br><hr><br>
                                    </div>
                                    <div class="col-md-12 box-marca">
                                        <div><img srcset="imagens/artworks/2048-{{$artworkVert->imagem}}" alt="{{$artworkVert->title}}" title="{{$artworkVert->title}}" width="100%" ></div>
                                        <h5>2048px de largura × 796px de altura</h5><br>
                                        <i class="fa fa-download fa-2x" style="color: #3498DB;" aria-hidden="true"></i><br><br>
                                        <p><a href="imagens/artworks/2048-{{$artworkVert->imagem}}" target="_blank">PNG</a> | <a href="imagens/artworks/2048-{{$artworkVertJpg->imagem}}" target="_blank">JPG</a></p>
                                    </div>
                                </div>
                                {{--//////////--}}
                            </div>
                            <div role="tabpanel" class="tab-pane" id="impressao">
                                <div class="row">
                                    @foreach($printings as $printing)
                                        <br><br>
                                        <div class="col-md-6 text-center">
                                            <img srcset="imagens/printings/md-{{$printing->imagem}}" alt="{{$printing->title}}" title="{{$printing->title}}" width="100%" >
                                            <h3>{{$printing->title}}</h3><br>
                                            <a href="arquivos/printings/{{$printing->arquivo}}">
                                                <i class="fa fa-download fa-2x" style="color: #3498DB;" aria-hidden="true"></i><br>Download<br>
                                            </a>
                                            <br>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div role="tabpanel" class="tab-pane" id="manual">
                                @if($printingsManual)
                                <iframe src="arquivos/printings/{{$printingsManual->arquivo}}" height="1000px" width="100%" frameborder="0"></iframe>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                <style>
                    .img-user{
                        border-radius: 50%;
                        width: 40px;
                        border: solid 2px #CCCCCC;
                        margin-right: 10px;
                    }
                </style>

                {{--////////////////////Equipe START////////////////////--}}
                    @foreach($versoes as $versao)
                        <?php
                        $cordenadores = DB::table('integrantes')
                            ->select('integrantes.id', 'integrantes.titulo', 'integrantes.imagem', 'integrantes.url')
                            ->join('items_versoes', 'integrantes.id', '=', 'items_versoes.integrante_id')
                            ->where('items_versoes.versao_id', $versao->id)
                            ->where('items_versoes.tipo_id', 1)
                            ->where('items_versoes.status', 1)
                            ->get();
                        $equipe = DB::table('integrantes')
                            ->select('integrantes.id', 'integrantes.titulo', 'integrantes.imagem', 'integrantes.url')
                            ->join('items_versoes', 'integrantes.id', '=', 'items_versoes.integrante_id')
                            ->where('items_versoes.versao_id', $versao->id)
                            ->where('items_versoes.tipo_id', 2)
                            ->where('items_versoes.status', 1)
                            ->get();

                        ?>
                        <h2>{{$versao->titulo}}</h2>


                                <div><strong>Coordenadores:</strong>
                                    @foreach($cordenadores as $cordenador)
                                        <a href="{{$cordenador->url}}">
                                            <div>
                                                @if($cordenador->imagem)
                                                <img src="imagens/integrantes/xs-{{$cordenador->imagem}}" alt="{{$cordenador->titulo}}" title="{{$cordenador->titulo}}" class="img-user">
                                                @else
                                                <img src="http://evbsb1052.ipea.gov.br/atlasviolencia/img/marker.png" class="img-user">
                                                @endif
                                                {{$cordenador->titulo}}
                                            </div>
                                        </a>
                                    @endforeach
                                </div>


                                <br>    
                                <div>
                                    <strong>Equipe Técnica:</strong>
                                    <div style="clear: both;"></div>
                                    @foreach($equipe as $integrante)
                                    <div class="box-integrante">
                                        <a href="{{$integrante->url}}" target="_blank">
                                            @if($integrante->imagem)
                                                <img src="imagens/integrantes/xs-{{$integrante->imagem}}" alt="{{$integrante->titulo}}" title="{{$integrante->titulo}}" class="img-user">
                                            @else
                                                <img src="http://evbsb1052.ipea.gov.br/atlasviolencia/img/marker.png" class="img-user">
                                            @endif
                                            {{$integrante->titulo}}                                            
                                        </a>
                                    </div>                                   
                                    @endforeach
                                     <div style="clear: both;"></div>
                                </div>


                    @endforeach
                    <style type="text/css">
                        .box-integrante{
                            float: left;
                            margin: 0 10px 0 0;
                        }
                        .img-user{
                            width:50px;
                            height: 50px;
                            margin: 5px 10px;
                            border: solid 2px #CCCCCC; 
                        }
                    </style>
                {{--////////////////////Equipe END////////////////////--}}



    <style>
        .box-marca h5{
            font-weight: bold;
            font-size: 12px;
        }
        .box-marca div:nth-child(1){

        }
    </style>
            </div>
        </div>
    </div>
@endsection

