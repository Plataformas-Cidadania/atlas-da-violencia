@extends('layout')
@section('title', 'Bem Vindo')
@section('content')

    <div class="container">
        <div class="row">
            <div class="menu-local col-md-3 col-sm-3 hidden-xs">
                <h2 class="bg-qui"> <i class="fa fa-square ft-sec" aria-hidden="true"></i> ipeadata</h2>
                <ul>
                    <li><a href="">O que é</a>
                        {{--<ul ng-init="items2 = [1,2,3,4,5,6,7,8]">
                            <li style=" border: solid 1px #EEEEEE; display: block; padding: 5px;" >
                                <h2>ipeadata</h2>
                                <div ng-if="items2.length>10">
                                    <input type="text" class="form-control " ng-model="search2" style="padding-right: 30px;">
                                    <i class="fa fa-search" aria-hidden="true"></i>
                                </div>

                            </li>
                            <li ng-repeat="item in items2 | filter:search2"><% item %></li>
                        </ul>--}}
                    </li>
                    <li><a href="">Equipe responsável</a></li>
                    <li><a href="">Metadados</a></li>
                    <li><a href="">Direito de uso</a></li>
                    <li><a href="">Dicas</a></li>
                    <li><a href="">Links</a></li>
                    <li><a href="">Fale Conosco</a></li>
                </ul>

                <h2 class="bg-qui"> <i class="fa fa-square" aria-hidden="true"></i> Meus acessos</h2>
                <ul>
                    <li><a href=""><i class="fa fa-square ft-sec" aria-hidden="true"></i> O que é</a></li>
                    <li><a href=""><i class="fa fa-square ft-sec" aria-hidden="true"></i> Equipe responsável</a></li>
                    <li><a href=""><i class="fa fa-square ft-ter" aria-hidden="true"></i> Metadados</a></li>
                    <li><a href=""><i class="fa fa-square ft-ter" aria-hidden="true"></i> Direito de uso</a></li>
                    <li><a href=""><i class="fa fa-square ft-ter" aria-hidden="true"></i> Dicas</a></li>
                    <li><a href=""><i class="fa fa-square ft-ter" aria-hidden="true"></i> Links</a></li>
                    <li><a href=""><i class="fa fa-square ft-qua" aria-hidden="true"></i> Fale Conosco</a></li>
                </ul>

                <h2 class="bg-qui">
                    <i class="fa fa-square ft-pri" aria-hidden="true"></i> Macroeconômico
                </h2>
                <ul>
                    <?php
                        foreach($menu as $item){
                            $submenu = \App\Menu::where('menu_id', $item->id)->get();
                            $item->submenu = $submenu;
                            if(count($submenu)>0){
                                foreach($submenu as $item){
                                    $submenu2 = \App\Menu::where('menu_id', $item->id)->get();
                                    $item->submenu = $submenu2;
                                    if(count($submenu2)>0){
                                        foreach($submenu2 as $item){
                                            $submenu3 = \App\Menu::where('menu_id', $item->id)->get();
                                            $item->submenu = $submenu3;
                                        }
                                    }
                                }
                            }
                        }
                    ?>

                    <div ng-init="items={{json_encode($menu)}}"></div>
                    <li ng-repeat="item in items track by $index">
                        <a href="" ng-bind="item.title"></a>
                        <ul ng-if="item.submenu.length>0" class="dropdown-content">
                            <li style=" border: solid 1px #EEEEEE; display: block; padding: 5px;" >
                                <h2 ng-bind="item.title"></h2>
                                <div ng-show="item.submenu.length > 2">
                                    <input type="text" class="form-control" ng-model="search[item.id].title" style="padding-right: 30px;">
                                    <i class="fa fa-search" aria-hidden="true"></i>
                                </div>
                            </li>
                            <li ng-repeat="item in item.submenu | filter:search[item.id] track by $index">
                                <a href="" ng-bind="item.title"></a>
                                <ul ng-if="item.submenu.length>0" class="dropdown-content-sub1">
                                    <li style=" border: solid 1px #EEEEEE; display: block; padding: 5px;" >
                                        <h2 ng-bind="item.title"></h2>
                                        <div ng-show="item.submenu.length > 2">
                                            <input type="text" class="form-control" ng-model="search[item.id].title" style="padding-right: 30px;">
                                            <i class="fa fa-search" aria-hidden="true"></i>
                                        </div>
                                    </li>
                                    <li ng-repeat="item in item.submenu | filter:search[item.id] track by $index">
                                        <a href="" ng-bind="item.title"></a>
                                        <ul ng-if="item.submenu.length>0" class="dropdown-content-sub2">
                                            <li style=" border: solid 1px #EEEEEE; display: block; padding: 5px;" >
                                                <h2 ng-bind="item.title"></h2>
                                                <div ng-show="item.submenu.length > 2">
                                                    <input type="text" class="form-control" ng-model="search[item.id].title" style="padding-right: 30px;">
                                                    <i class="fa fa-search" aria-hidden="true"></i>
                                                </div>
                                            </li>
                                            <li ng-repeat="item in item.submenu | filter:search[item.id] track by $index">
                                                <a href="" ng-bind="item.title"></a>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>

                    {{--<li><a href="">Metadados</a> </li>
                    <li><a href="">Direito de uso</a></li>
                    <li><a href="">Dicas</a></li>
                    <li><a href="">Links</a></li>
                    <li><a href="">Fale Conosco</a></li>


                    <li>
                        <a href="">O que é</a>
                            <ul class="dropdown-content">
                                <li>
                                    <h2>ipeadata</h2>
                                    <input type="text" class="form-control " style="padding-right: 30px;">
                                    <i class="fa fa-search" aria-hidden="true"></i>
                                </li>
                                <li>AAAAAA</li>
                                <li>BBBBBB</li>
                                <li>CCCCCC
                                    <ul class="dropdown-content-duos">
                                        <li>
                                            <h2>ipeadata</h2>
                                            <input type="text" class="form-control " style="padding-right: 30px;">
                                            <i class="fa fa-search" aria-hidden="true"></i>
                                        </li>
                                        <li>11111</li>
                                        <li>22222</li>
                                        <li>33333
                                            <ul class="dropdown-content-ter">
                                                <li>
                                                    <h2>ipeadata</h2>
                                                    <input type="text" class="form-control " style="padding-right: 30px;">
                                                    <i class="fa fa-search" aria-hidden="true"></i>
                                                </li>
                                                <li>11111</li>
                                                <li>22222</li>
                                                <li>33333</li>
                                                <li>44444</li>
                                            </ul>
                                        </li>
                                        <li>44444</li>
                                    </ul>
                                </li>
                                <li>DDDDDD</li>
                            </ul>
                            --}}{{--<search-menu items-title="'ipeadata'" items="[1,2,3,4,5,6]"></search-menu>--}}{{--
                        </li>--}}


                </ul>

                {{--<h2 class="bg-qui"> <i class="fa fa-square ft-sec" aria-hidden="true"></i> Macroeconômico</h2>
                <ul>
                    <li><a href="">O que é</a>
                    </li>
                    <li><a href="">Equipe responsável</a></li>
                    <li><a href="">Metadados</a></li>
                    <li><a href="">Direito de uso</a></li>
                    <li><a href="">Dicas</a></li>
                    <li><a href="">Links</a></li>
                    <li><a href="">Fale Conosco</a></li>
                </ul>--}}
                <h2 class="bg-qui"> <i class="fa fa-square ft-ter" aria-hidden="true"></i> Regional</h2>
                <ul>
                    <li><a href="">O que é</a>
                        {{--<ul ng-init="items2 = [1,2,3,4,5,6,7,8]">
                            <li style=" border: solid 1px #EEEEEE; display: block; padding: 5px;" >
                                <h2>ipeadata</h2>
                                <div ng-if="items2.length>10">
                                    <input type="text" class="form-control " ng-model="search2" style="padding-right: 30px;">
                                    <i class="fa fa-search" aria-hidden="true"></i>
                                </div>

                            </li>
                            <li ng-repeat="item in items2 | filter:search2"><% item %></li>
                        </ul>--}}
                    </li>
                    <li><a href="">Equipe responsável</a></li>
                    <li><a href="">Metadados</a></li>
                    <li><a href="">Direito de uso</a></li>
                    <li><a href="">Dicas</a></li>
                    <li><a href="">Links</a></li>
                    <li><a href="">Fale Conosco</a></li>
                </ul>
                <h2 class="bg-qui"> <i class="fa fa-square ft-qua" aria-hidden="true"></i> Social</h2>
                <ul>
                    <li><a href="">O que é</a>
                        {{--<ul ng-init="items2 = [1,2,3,4,5,6,7,8]">
                            <li style=" border: solid 1px #EEEEEE; display: block; padding: 5px;" >
                                <h2>ipeadata</h2>
                                <div ng-if="items2.length>10">
                                    <input type="text" class="form-control " ng-model="search2" style="padding-right: 30px;">
                                    <i class="fa fa-search" aria-hidden="true"></i>
                                </div>

                            </li>
                            <li ng-repeat="item in items2 | filter:search2"><% item %></li>
                        </ul>--}}
                    </li>
                    <li><a href="">Equipe responsável</a></li>
                    <li><a href="">Metadados</a></li>
                    <li><a href="">Direito de uso</a></li>
                    <li><a href="">Dicas</a></li>
                    <li><a href="">Links</a></li>
                    <li><a href="">Fale Conosco</a></li>
                </ul>
            </div>
            <div class="col-md-9 col-sm-9">
                <div class="row">
                    <?php $cont = 0;?>
                @foreach($links as $link)
                <div class="col-md-4 col-sm-12 box-itens " ng-class="{'alto-contraste': altoContrasteAtivo}" >
                    <div class="@if($cont==0)bg-sec @elseif($cont==1)bg-ter @else bg-qua @endif">
                        <a href="{{$link->link}}" target="_blank">
                            <h2 class="titulo-itens" ng-class="{'alto-contraste': altoContrasteAtivo}" href="{{$link->link}}">{{$link->titulo}}</h2>
                            <picture>
                                <source srcset="/imagens/links/{{$link->imagem}}" media="(max-width: 468px)">
                                <source srcset="/imagens/links/{{$link->imagem}}" media="(max-width: 768px)">
                                <source srcset="/imagens/links/{{$link->imagem}}" class="img-responsive">
                                <img srcset="/imagens/links/{{$link->imagem}}" alt="Imagem sobre {{$link->titulo}}" title="Imagem sobre {{$link->titulo}}" >
                            </picture>
                            <div class="bg-sex">
                                <br>
                                <p ng-class="{'alto-contraste': altoContrasteAtivo}" href="{{$link->link}}">{{$link->descricao}}</p>
                            </div>
                           <div class="box-itens-filete"></div>
                        </a>
                    </div>
                </div>
                <?php $cont ++;?>
                @endforeach
            </div>

                <div class="row box_txt text-left bg-qui">
                    <div class="col-md-12">
                        <a href="/quem/conheca-o-ipea" >
                            <p>{{strip_tags($bemvindo->descricao)}}</p>
                        </a>
                    </div>
                </div>

                <br><br><br>

               {{-- http://ionden.com/a/plugins/ion.rangeSlider/demo_interactions.html
               http://www.w3schools.com/jquerymobile/jquerymobile_form_sliders.asp--}}

                    <canvas id="myChart" width="400" height="200"></canvas>
                    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.1.3/Chart.min.js"></script>
                    <script>
                        var canvas = document.getElementById('myChart');
                        var data = {
                            //labels: ["January", "February", "March", "April", "May", "June", "July"],
                            labels: ["2010", "2011", "2012", "2013", "2014", "2015", "2016"],
                            datasets: [
                                {
                                    label: "Bovespa",
                                    fill: false,
                                    lineTension: 0.1,
                                    backgroundColor: "rgba(75,192,192,0.4)",
                                    borderColor: "rgba(75,192,192,1)",
                                    borderCapStyle: 'butt',
                                    borderDash: [],
                                    borderDashOffset: 0.0,
                                    borderJoinStyle: 'miter',
                                    pointBorderColor: "rgba(75,192,192,1)",
                                    pointBackgroundColor: "#fff",
                                    pointBorderWidth: 1,
                                    pointHoverRadius: 5,
                                    pointHoverBackgroundColor: "rgba(75,192,192,1)",
                                    pointHoverBorderColor: "rgba(220,220,220,1)",
                                    pointHoverBorderWidth: 2,
                                    pointRadius: 5,
                                    pointHitRadius: 10,
                                    data: [65, 59, 80, 0, 56, 55, 40],
                                }
                            ]
                        };

                        var option = {
                            showLines: true
                        };
                        var myLineChart = Chart.Line(canvas,{
                            data:data,
                            options:option
                        });
                    </script>

                <div class="row">
                    <h2 class="box-titulo">Notícias</h2>
                    @foreach($noticias as $noticia)
                        <div class="col-md-4">
                            <a href="noticia/{{$noticia->id}}/{{clean($noticia->titulo)}}" aria-label="{{$noticia->titulo}}, {{str_limit(strip_tags($noticia->descricao), 180)}}, continue lendo a matéria">
                                <h3 class="h3-m">{{$noticia->titulo}}</h3>
                                <p>{{str_limit(strip_tags($noticia->descricao), 180)}}</p>
                            </a>
                        </div>
                    @endforeach
                    <div class="row text-center">
                        <div class="col-md-12 space-top">
                            <a href="/noticias/veja-todas-as-noticias" role="button">
                                <button class="btn btn-sec btn-padding btn-base">VER MAIS NOTÍCIAS</button>
                            </a>
                        </div>
                    </div>
                </div>



            </div>


        </div>
    </div>

    <article>




    </article>

@endsection


