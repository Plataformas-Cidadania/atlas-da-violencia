<?php
$base_href = config('app.url');
/*$base_href = $_SERVER['HTTP_HOST'];
if(substr($base_href, 0,9)=='evbsb1052'){
    $base_href .= '/atlasviolencia/';
}*/

$setting = DB::table('settings')->orderBy('id', 'desc')->first();
?>
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
                    <img src="assets-cms/img/logo-b-p.png" width="95" alt="">
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

            <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
            <div class="collapse navbar-collapse navbar-ex1-collapse">
                <ul class="nav navbar-nav side-nav">
                    <li class="active">
                        <a href="cms"><i class="fa fa-fw fa-dashboard"></i> Dashboard</a>
                    </li>


                    <li>
                        <a href="cms/fontes"><i class="fa fa-university" aria-hidden="true"></i> Fontes</a>
                    </li>
                    <li>
                        <a href="cms/temas/0"><i class="fa fa-folder-open" aria-hidden="true"></i> Temas</a>
                    </li>
                    <li>
                        <a href="cms/indicadores"><i class="fa fa-indent" aria-hidden="true"></i> Indicadores</a>
                    </li>

                    <li>
                        <a href="cms/series"><i class="fa fa-cubes" aria-hidden="true"></i> Séries</a>
                    </li>


                    <li>
                        <a href="javascript:;" data-toggle="collapse" data-target="#demo"><i
                                    class="fa fa-fw fa-arrows-v"></i> Conteudo <i
                                    class="fa fa-fw fa-caret-down"></i></a>
                        <ul id="demo" class="collapse">
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
                        </ul>
                    </li>

                    <li>
                        <a href="javascript:;" data-toggle="collapse" data-target="#demo1"><i
                                    class="fa fa-fw fa-arrows-v"></i> Marca <i class="fa fa-fw fa-caret-down"></i></a>
                        <ul id="demo1" class="collapse">
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
                        <a href="javascript:;" data-toggle="collapse" data-target="#demo0"><i
                                    class="fa fa-fw fa-arrows-v"></i> Admin <i class="fa fa-fw fa-caret-down"></i></a>
                        <ul id="demo0" class="collapse">
                            <li>
                                <a href="cms/idiomas"><i class="fa fa-language" aria-hidden="true"></i> Idiomas</a>
                            </li>
                            <li>
                                <a href="cms/unidades"><i class="fa fa-indent" aria-hidden="true"></i> Unidades</a>
                            </li>
                            <li>
                                <a href="cms/usuarios"><i class="fa fa-fw fa-users"></i> Usuários</a>
                            </li>
                        </ul>
                    </li>

                    <li>
                        <a href="javascript:;" data-toggle="collapse" data-target="#demo2"><i
                                    class="fa fa-fw fa-arrows-v"></i> Layout <i class="fa fa-fw fa-caret-down"></i></a>
                        <ul id="demo2" class="collapse">
                            <li>
                                <a href="cms/webdoors"><i class="fa fa-fw fa-desktop"></i> Webdoors</a>
                            </li>
                            {{--<li>
                                <a href="cms/quemsomos"><i class="fa fa-fw fa-building"></i> Modulos</a>
                            </li>--}}
                            <li>
                                <a href="cms/apoios"><i class="fa fa-fw fa-anchor"></i> Apoio</a>
                            </li>
                            <li>
                                <a href="cms/links"><i class="fa fa-fw fa-link"></i> Links</a>
                            </li>
                            <li>
                                <a href="cms/indices"><i class="fa fa-indent" aria-hidden="true"></i> Índices</a>
                            </li>
                            <li>
                                <a href="cms/webindicadores"><i class="fa fa-indent" aria-hidden="true"></i> Indicadores
                                    Site</a>
                            </li>
                            <li>
                                <a href="cms/setting"><i class="fa fa-fw fa-cog"></i> Configurações</a>
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
