<?php
$base_href = config('app.url');
/*$base_href = $_SERVER['HTTP_HOST'];
if(substr($base_href, 0,9)=='evbsb1052'){
    $base_href .= '/atlasviolencia/';
}*/

$setting = DB::table('settings')->orderBy('id', 'desc')->first();
$favicons = DB::table('favicons')->orderBy('id', 'desc')->get();

$mensagensContato = DB::table('mensagens')->where('status', 0)->where('origem', 'contato')->count();
$mensagensSerie = DB::table('mensagens')->where('status', 0)->where('origem', 'serie')->count();
?>



<?php $rota = Route::getCurrentRoute()->getPath();?>




        <!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>CMS - {{$setting->titulo}}</title>
    <base href="http://{{$base_href}}@if($base_href=='10.0.52.46')/@endif">

    <!-- Bootstrap Core CSS -->
    <link href="assets-cms/lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="assets-cms/css/sb-admin.css" rel="stylesheet">
    <link href="assets-cms/css/cms.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="assets-cms/lib/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    @include('cms::conexoes.css')
    @include('cms::conexoes.js')

    <link rel="icon" href="/assets-cms/img/favicons/icon_16x16.png" sizes="16x16">
    <link rel="icon" href="/assets-cms/img/favicons/icon_32x32.png" sizes="32x32">
    <link rel="icon" href="/assets-cms/img/favicons/icon_48x48.png" sizes="48x48">
    <link rel="icon" href="/assets-cms/img/favicons/icon_64x64.png" sizes="64x64">
    <link rel="icon" href="/assets-cms/img/favicons/icon_72x72.png" sizes="72x72">
    <link rel="icon" href="/assets-cms/img/favicons/icon_96x96.png" sizes="96x96">
    <link rel="icon" href="/assets-cms/img/favicons/icon_114x114.png" sizes="114x114">
    <link rel="icon" href="/assets-cms/img/favicons/icon_128x128.png" sizes="128x128">
    <link rel="icon" href="/assets-cms/img/favicons/icon_144x144.png" sizes="144x144">
    <link rel="icon" href="/assets-cms/img/favicons/icon_256x256.png" sizes="256x256">

</head>

<body ng-app="cmsApp" style="background-color: #FFF;">
@if (!auth()->guard('cms')->guest())
    <div id="wrapper">

        <!-- Navigation -->
        <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="cms">
                    {{--<img src="assets-cms/img/logo-b-p.png" width="95" alt="">--}}
                    <img src="assets-cms/img/logo-cms.png" width="110" alt="" style="margin-top: -5px;">
                </a>
            </div>
            <!-- Top Menu Items -->
            <ul class="nav navbar-right top-nav">

                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i
                                class="fa fa-user"></i> {{auth()->guard('cms')->user()->name}} <b class="caret"></b></a>
                    <ul class="dropdown-menu" style="min-width: 200px;">
                        <li>
                            <a href="cms/perfil"><i class="fa fa-fw fa-user"></i> Perfil</a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="cms/logout"><i class="fa fa-fw fa-power-off"></i> Log Out</a>
                        </li>
                    </ul>
                </li>

            </ul>
<style>
    


    .icon-cms-user{
        border-radius: 50%;
        width: 100px;
        height: 100px;
        background-color: #FFFFFF;
        padding: 10px;
        margin: 20px auto;
    }
</style>
            <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
            <div class="collapse navbar-collapse navbar-ex1-collapse">
                <ul class="nav navbar-nav side-nav">

                        <div class="text-center icon-cms-user">
                            @foreach($favicons as $favicon)
                                <img src="imagens/favicons/{{$favicon->imagem}}" alt="" width="100%">
                            @endforeach
                        </div>
                        <div class="text-center" style="height: 50px;"><strong style="color: #FFFFFF;">{{$setting->titulo}}</strong></div>

                    <li class="active">
                        <a href="cms"><i class="fa fa-fw fa-dashboard"></i> Dashboard</a>
                    </li>
                    
                    <li>
                        <a href="javascript:;" data-toggle="collapse" data-target="#demo8"><i class="fa fa-envelope" aria-hidden="true"></i> Mensagens <div class="qtd_emails">{{$mensagensContato+$mensagensSerie}}</div><i class="fa fa-fw fa-caret-down"></i></a>
                        <ul id="demo8" class="collapse @if($rota=="cms/mensagens/{origem}") show @endif">
                            <li>
                                <a href="cms/mensagens/contato">Contato <div class="qtd_emails">{{$mensagensContato}}</div></a>
                            </li>
                            <li>
                                <a href="cms/mensagens/serie">Séries <div class="qtd_emails">{{$mensagensSerie}}</div></a>
                            </li>
                        </ul>
                    </li>

                    <li>
                        <a href="cms/temas"><i class="fa fa-folder-open" aria-hidden="true"></i> Temas</a>
                    </li>
                    

                    <li>
                        <a href="cms/series"><i class="fa fa-cubes" aria-hidden="true"></i> Séries</a>
                    </li>

                    {{--<li>
                        <a href="javascript:;" data-toggle="collapse" data-target="#demo6"><i class="fa fa-fw fa-user"></i> Consultas <i class="fa fa-fw fa-caret-down"></i></a>
                        <ul id="demo6" class="collapse @if($rota=="cms/consultas") show @endif">
                            <li>
                                <a href="cms/quemsomos/9/consultas">Descrição</a>
                            </li>
                            <li>
                                <a href="cms/consultas"><i class="fa fa-cubes" aria-hidden="true"></i> Consultas</a>
                            </li>
                        </ul>
                    </li>--}}

                    <li>
                        <a href="javascript:;" data-toggle="collapse" data-target="#demo7"><i class="fa fa-indent" aria-hidden="true"></i> Indicadores Site <i class="fa fa-fw fa-caret-down"></i></a>
                        <ul id="demo7" class="collapse @if($rota=="cms/webindicadores") show @endif">
                            <li>
                                <a href="cms/quemsomos/5/webindicador">Descrição</a>
                            </li>
                            <li>
                                <a href="cms/webindicadores"><i class="fa fa-indent" aria-hidden="true"></i> Indicadores</a>
                            </li>
                        </ul>
                    </li>

                    <li>
                        <a href="javascript:;" data-toggle="collapse" data-target="#demo4"><i
                                    class="fa fa-fw fa-arrows-v"></i> Pontos <i class="fa fa-fw fa-caret-down"></i></a>
                        <ul id="demo4" class="collapse @if($rota=="cms/filtros") show @endif">
                            <li>
                                <a href="cms/filtros"><i class="fa fa-fw fa-desktop"></i> Filtros</a>
                            </li>


                        </ul>
                    </li>


                    <li>
                        <a href="javascript:;" data-toggle="collapse" data-target="#demo"><i class="fa fa-fw fa-arrows-v"></i> Conteudo <i class="fa fa-fw fa-caret-down"></i></a>
                        <ul id="demo" class="collapse  @if($rota=="cms/noticias" || $rota=="cms/authors" || $rota=="cms/artigos" || $rota=="cms/videos" || $rota=="cms/downloads" || $rota=="cms/webdoors" ) show @endif">
                            <li>
                                <a href="cms/presentations"><i class="fa fa-fw fa-newspaper-o"></i> Apresentações</a>
                            </li>
                            <li>
                                <a href="cms/noticias"><i class="fa fa-fw fa-newspaper-o"></i> Notícias</a>
                            </li>
                            <li>
                                <a href="cms/authors"><i class="fa fa-user" aria-hidden="true"></i> Autores</a>
                            </li>
                            <li>
                                <a href="cms/artigos"><i class="fa fa-file-text-o" aria-hidden="true"></i> Artigos</a>
                            </li>
                            <li>
                                <a href="cms/videos"><i class="fa fa-youtube" aria-hidden="true"></i> Vídeos</a>
                            </li>
                            <li>
                                <a href="cms/downloads"><i class="fa fa-download" aria-hidden="true"></i> Downloads</a>
                            </li>
                            <li>
                                <a href="cms/webdoors"><i class="fa fa-fw fa-desktop"></i> Webdoors</a>
                            </li>
                        </ul>
                    </li>

                    

                    <li>
                        <a href="javascript:;" data-toggle="collapse" data-target="#demo0"><i
                                    class="fa fa-fw fa-arrows-v"></i> Admin <i class="fa fa-fw fa-caret-down"></i></a>
                        <ul id="demo0" class="collapse @if($rota=="cms/idiomas" || $rota=="cms/fontes" || $rota=="cms/unidades" || $rota=="cms/periodicidades" || $rota=="cms/indicadores" || $rota=="cms/options-abrangencias" || $rota=="cms/padrao-territorios" || $rota=="cms/directives" || $rota=="cms/artworks" || $rota=="cms/printings" || $rota=="cms/integrantes" || $rota=="cms/versoes") show @endif">
                            <li>
                                <a href="cms/idiomas"><i class="fa fa-language" aria-hidden="true"></i> Idiomas</a>
                            </li>
                            <li>
                                <a href="cms/fontes"><i class="fa fa-university" aria-hidden="true"></i> Fontes</a>
                            </li>
                            <li>
                                <a href="cms/unidades"><i class="fa fa-indent" aria-hidden="true"></i> Unidades</a>
                            </li>
                            <li>
                                <a href="cms/periodicidades"><i class="fa fa-indent" aria-hidden="true"></i> Periodicidade</a>
                            </li>
                            <li>
                                <a href="cms/indicadores"><i class="fa fa-indent" aria-hidden="true"></i> Indicadores</a>
                            </li>
                            <li>
                                <a href="cms/options-abrangencias"><i class="fa fa-indent" aria-hidden="true"></i> Options Abrangencias</a>
                            </li>
                            <li>
                                <a href="cms/padrao-territorios"><i class="fa fa-indent" aria-hidden="true"></i> Padrão Territorio</a>
                            </li>
                            

                            <li>
                                <a href="javascript:;" data-toggle="collapse" data-target="#demo5"><i class="fa fa-fw fa-user"></i> Equipe <i class="fa fa-fw fa-caret-down"></i></a>
                                <ul id="demo5" class="collapse @if($rota=="cms/integrantes" || $rota=="cms/versoes") show @endif">
                                    <li>
                                        <a href="cms/quemsomos/7/equipe">Descrição</a>
                                    </li>
                                    <li>
                                        <a href="cms/integrantes">Integrantes</a>
                                    </li>
                                    <li>
                                        <a href="cms/versoes">Versões</a>
                                    </li>
                                </ul>
                            </li>



                            <li>
                                <a href="javascript:;" data-toggle="collapse" data-target="#demo1"><i
                                            class="fa fa-fw fa-arrows-v"></i> Webservice  <i class="fa fa-fw fa-caret-down"></i></a>
                                <ul id="demo1" class="collapse @if($rota=="cms/apis") show @endif">
                                    <li>
                                        <a href="cms/quemsomos/10/api">Descricao</a>
                                    </li>
                                    <li>
                                        <a href="cms/apis"><i class="fa fa-indent" aria-hidden="true"></i> Api</a>
                                    </li>
                                </ul>
                            </li>

                            <style>

                            .side-nav>li>ul>li>ul {
                                margin:0;
                                padding: 0;                     
                            }

                            .side-nav>li>ul>li>ul>li {   
                                text-decoration: none;
                                list-style-type:none; 
                            
                            }

                            .side-nav>li>ul>li>ul>li>a {
                                padding: 10px 15px 10px 38px;
                                color: #D2E9FF!important;
                                display: block;
                                text-decoration: none;
                            }
                        </style>

                            <li>
                                <a href="javascript:;" data-toggle="collapse" data-target="#demo-marca"><i
                                            class="fa fa-fw fa-arrows-v"></i> Marca <i class="fa fa-fw fa-caret-down"></i></a>
                                <ul id="demo-marca" class="collapse @if($rota=="cms/directives" || $rota=="cms/artworks" || $rota=="cms/printings") show @endif">
                                    <li>
                                        <a href="cms/quemsomos/8/marca">Descrição</a>
                                    </li>
                                    <li>
                                        <a href="cms/directives">Diretivas</a>
                                    </li>
                                    <li>
                                        <a href="cms/artworks">Artes</a>
                                    </li>
                                    <li>
                                        <a href="cms/printings">Impressões</a>
                                    </li>
                                </ul>
                            </li>

                            <li>
                                <a href="cms/usuarios"><i class="fa fa-fw fa-users"></i> Usuários</a>
                            </li>
                            <li>
                                <a href="cms/setting"><i class="fa fa-fw fa-cog"></i> Configurações</a>
                            </li>


                        </ul>
                    </li>

                    <li>
                        <a href="javascript:;" data-toggle="collapse" data-target="#demo2"><i
                                    class="fa fa-fw fa-arrows-v"></i> Layout <i class="fa fa-fw fa-caret-down"></i></a>
                        <ul id="demo2" class="collapse  @if($rota=="cms/menus" || $rota=="cms/apoios" || $rota=="cms/parceiros" || $rota=="cms/links" || $rota=="cms/indices" || $rota=="cms/setting" || $rota=="cms/favicons" ) show @endif">
                            <li>
                                <a href="cms/menus"><i class="fa fa-fw fa-bars"></i> Menu</a>
                            </li> 
                            <li>
                                <a href="cms/apoios"><i class="fa fa-fw fa-anchor"></i> Apoio</a>
                            </li>
                            <li>
                                <a href="cms/parceiros"><i class="fa fa-fw fa-anchor"></i> Parceiros</a>
                            </li>
                            <li>
                                <a href="cms/links"><i class="fa fa-fw fa-link"></i> Links</a>
                            </li>
                            <li>
                                <a href="cms/indices"><i class="fa fa-indent" aria-hidden="true"></i> Índices</a>
                            </li>            
                            <li>
                                <a href="cms/setting"><i class="fa fa-fw fa-cog"></i> Configurações</a>
                            </li>

                            <li>
                                <a href="cms/favicons"><i class="fa fa-fw fa-cog"></i> Favicons</a>
                            </li>

                        </ul>
                    </li>

                    <li>
                        <a href="javascript:;" data-toggle="collapse" data-target="#demo3"><i
                                    class="fa fa-fw fa-arrows-v"></i> Modulos <i class="fa fa-fw fa-caret-down"></i></a>
                        <ul id="demo3" class="collapse">
                            <li>
                                <a href="cms/quemsomos/0/bem-vindo"><i class="fa fa-fw fa-desktop"></i> Bem vindo</a>
                            </li>
                            <li>
                                <a href="cms/quemsomos/1/institucional"><i class="fa fa-fw fa-building"></i> Institucional</a>
                            </li>
                            <li>
                                <a href="cms/quemsomos/2/acessibilidade"><i class="fa fa-fw fa-building"></i> Acessibilidade</a>
                            </li>
                            <li>
                                <a href="cms/quemsomos/3/redirecionamento"><i class="fa fa-fw fa-building"></i> Redirecionamento</a>
                            </li>
                            <li>
                                <a href="cms/quemsomos/4/publicações"><i class="fa fa-fw fa-building"></i> Home Publicações</a>
                            </li>
                            <li>
                                <a href="cms/quemsomos/6/menu"><i class="fa fa-fw fa-building"></i> Páginas</a>
                            </li>

                        </ul>
                    </li>
                    
                    

                    

                    


                    <!--<li>
                        <a href="blank-page.html"><i class="fa fa-fw fa-file"></i> Blank Page</a>
                    </li>-->

                </ul>
            </div>

            <!-- /.navbar-collapse -->
        </nav>

        <div id="page-wrapper">


            <div class="col-md-12">
                @yield('content')
            </div>


        <!-- /.container-fluid -->

        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->
@else
    @yield('content')
@endif


</body>

</html>

<style>
    .qtd_emails{
        float: right;
        background-color: #ff8800;
        color: #FFFFFF;
        width: 18px;
        height: 18px;
        border-radius: 50%;
        text-align: center;
        margin-right: 10px;
    }
    .side-nav{
        background-color: #1B559F!important;
    }
    .navbar-inverse .navbar-nav>li>a {
        color: #D2E9FF!important;
    }
    .side-nav>li>ul>li>a {
        color: #D2E9FF!important;
    }
    .side-nav li a:hover, .side-nav li a:focus {
        outline: none;
        background-color: #1E508F !important;
    }
    .navbar-inverse .navbar-nav>.active>a, .navbar-inverse .navbar-nav>.active>a:focus, .navbar-inverse .navbar-nav>.active>a:hover {
        background-color: #1E508F !important;
    }
    .navbar-inverse {
        background-color: #F3F4F6!important;
        border-color: #F3F4F6!important
    }
    .top-nav>li>a:hover, .top-nav>li>a:focus, .top-nav>.open>a, .top-nav>.open>a:hover, .top-nav>.open>a:focus {
        background-color: #1B559F !important;
    }
    .navbar-inverse .navbar-toggle:focus, .navbar-inverse .navbar-toggle:hover {
        background-color: #1B559F !important;
    }
    .navbar-inverse .navbar-toggle {
        border-color: #1B559F !important;
    }



    .


    /*.side-nav {
        left: 60px;
        width: 60px;
        margin-left: -60px;
    }
    .side-nav li>a>i{
        font-size: 25px!important;
    }*/
</style>