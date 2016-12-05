@extends('.layout')
@section('title', 'Série')
@section('content')

    {{--{{ Counter::count('noticia') }}--}}
    <div class="container" id="serieCtrl" ng-controller="serieCtrl">

        <div class="row  hidden-print">
            <div class="col-md-7">
                <h1>Série</h1>
            </div>
            <div class="col-md-5 text-right">
                <i class="fa fa-file-excel-o fa-2x" aria-hidden="true"></i>
                <i class="fa fa-file-archive-o fa-2x" aria-hidden="true"></i>
                <i class="fa fa-file-text-o fa-2x" aria-hidden="true"></i>
                <i class="fa fa-file-text-o fa-2x" aria-hidden="true"></i>
                <a href="javascript:window.print();"><i class="fa fa-print fa-2x" aria-hidden="true"></i></a>
                <i class="fa fa-calculator fa-2x" aria-hidden="true"></i>
            </div>
        </div>
        <div class="line_title bg-pri"></div>



        <div class="text-right hidden-print">
            <i class="fa fa-minus-square-o fa-click" aria-hidden="true" ng-click="goBox0 = !goBox0"ng-hide="goBox0"></i>
            <i class="fa fa-plus-square-o fa-click" aria-hidden="true" ng-click="goBox0 = !goBox0" ng-show="goBox0"> Informação <hr></i>
        </div>

        <div ng-hide="goBox0">
            <div ng-repeat="serie in series" class="bs-callout" style="border-left-color: rgb(<% cores[serie.id] %>);" id="callout-type-dl-truncate">
                {{--<div class="text-right item-hidden">
                    <i class="fa fa-times-circle-o" aria-hidden="true"></i>
                </div>--}}
                <h4 ng-bind="serie.serie"></h4>
                <p>
                    <strong>Frequência:</strong> <span ng-bind="serie.frequencia"></span><br>
                    <strong>Fonte:</strong> <span ng-bind="serie.fonte"></span><br>
                    <strong>Unidade:</strong> <span ng-bind="serie.unidade"></span><br>
                    <strong>Comentário:</strong> <span ng-bind="serie.comentario"></span><br>
                    <strong>Atualizado em:</strong>  <span ng-bind="serie.atualizado"></span>
                </p>
            </div>
        </div>

        <div class="text-right hidden-print">
            <i class="fa fa-minus-square-o fa-click" aria-hidden="true" ng-click="goBox1 = !goBox1"ng-hide="goBox1"></i>
            <i class="fa fa-plus-square-o fa-click" aria-hidden="true" ng-click="goBox1 = !goBox1" ng-show="goBox1"> Gráfico <hr></i>
        </div>

        <div ng-hide="goBox1">
            <canvas id="myChart" width="400" height="200"></canvas>
            {{--http://www.chartjs.org/docs/--}}
            <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.1.3/Chart.min.js"></script>
        </div>


        <div class="text-right hidden-print">
            <i class="fa fa-minus-square-o fa-click" aria-hidden="true" ng-click="goBox2 = !goBox2"ng-hide="goBox2"></i>
            <i class="fa fa-plus-square-o fa-click" aria-hidden="true" ng-click="goBox2 = !goBox2" ng-show="goBox2"> Filtro <hr></i>
        </div>

        <div class="hidden-print" ng-hide="goBox2">
            <h4><i class="fa fa-calendar" aria-hidden="true"></i> Periodicidade</h4>
            <input type="text" id="range" value=""  name="range" ng-model="range" />
            <br>
            {{--<h4><i class="fa fa-money" aria-hidden="true"></i> Valor</h4>
            <input type="text" id="range2" value="" name="range2" />
            <br><br>--}}
        </div>


        <script>

//                var canvas = document.getElementById('myChart');
//                var data = {
//                    //labels: ["January", "February", "March", "April", "May", "June", "July"],
//                    labels: ["2010", "2011", "2012", "2013", "2014", "2015", "2016"],
//                    datasets: [
//                        {
//                            label: "Bovespa",
//                            fill: false,
//                            lineTension: 0.1,
//                            backgroundColor: "rgba(75,192,192,0.4)",
//                            borderColor: "rgba(75,192,192,1)",
//                            borderCapStyle: 'butt',
//                            borderDash: [],
//                            borderDashOffset: 0.0,
//                            borderJoinStyle: 'miter',
//                            pointBorderColor: "rgba(75,192,192,1)",
//                            pointBackgroundColor: "#fff",
//                            pointBorderWidth: 1,
//                            pointHoverRadius: 5,
//                            pointHoverBackgroundColor: "rgba(75,192,192,1)",
//                            pointHoverBorderColor: "rgba(220,220,220,1)",
//                            pointHoverBorderWidth: 2,
//                            pointRadius: 5,
//                            pointHitRadius: 10,
//                            data: [65, 59, 80, 0, 56, 55, 40],
//                        }
//                    ]
//                };
//
//                var option = {
//                    showLines: true
//                };
//                var myLineChart = Chart.Line(canvas,{
//                    data:data,
//                    options:option
//                });


        </script>

        <div class="text-right hidden-print">
            <i class="fa fa-minus-square-o fa-click" aria-hidden="true" ng-click="goBox3 = !goBox3"ng-hide="goBox3"></i>
            <i class="fa fa-plus-square-o fa-click" aria-hidden="true" ng-click="goBox3 = !goBox3" ng-show="goBox3"> Tabela <hr></i>
        </div>

        <div ng-hide="goBox3">
            <br>
            <div class="table-responsive">
                <table class="table table-bordered table table-hover ">
                    <thead>
                    <tr>
                        <th>QTD.</th>
                        <th>Data {{--<i class="fa fa-sort" aria-hidden="true"></i>--}}</th>
                        <th ng-repeat="serie in series">
                            <i class="fa fa-area-chart" style="color: rgb(<% cores[serie.id] %>);" aria-hidden="true"></i>

                            <% serie.serie %>
                        </th>

                    </tr>
                    </thead>
                    <tbody>
                    <tr ng-repeat="(key, item) in datesRange track by $index">
                        <th scope="row" ng-bind="$index+1"></th>
                        <td ng-bind="item.date"></td>
                        <td ng-repeat="serie in series" class="text-right">
                            <i ng-if="min[serie.id]==item[serie.id].valor" class="fa fa-minus-square fa-clean" aria-hidden="true"></i>
                            <i ng-if="max[serie.id]==item[serie.id].valor" class="fa fa-plus-square fa-clean" aria-hidden="true"></i>
                            <% item[serie.id].valor | number: 3 %>
                        </td>

                        {{--<td class="text-right" ng-bind="date[2].valor | number: 3"></td>
                        <td class="text-right" ng-bind="date[2].valor | number: 3"></td>--}}
                    </tr>

                    </tbody>
                </table>
            </div>

            {{--<div><% datesRange %></div>--}}

            <div class="row">
                <div class="col-md-12">
                    <i class="fa fa-minus-square fa-clean" aria-hidden="true"></i> Menor valor do período &nbsp;&nbsp;
                    <i class="fa fa-plus-square fa-clean" aria-hidden="true"></i> Maior valor do período
                </div>
            </div>
        </div>


        <div class="text-right hidden-print">
            <i class="fa fa-minus-square-o fa-click" aria-hidden="true" ng-click="goBox4 = !goBox4" ng-hide="goBox4"></i>
            <i class="fa fa-plus-square-o fa-click" aria-hidden="true" ng-click="goBox4 = !goBox4" ng-show="goBox4"> Média <hr></i>
        </div>

        <div class="row" ng-hide="goBox4">
            <div class="col-md-12">
                <br>
                <h4><i class="fa fa-calculator" aria-hidden="true"></i> Cálculos</h4>
                <br>
            </div>

            <div ng-repeat="serie in series" class="col-md-3 col-sm-4 text-center">
                <div class="box-media">
                    <div class="line_title" style="background-color: rgb(<% cores[serie.id] %>);"></div>
                    <h4 ng-bind="serie.serie"></h4>
                    <h3 ng-bind="range.replace(';', ' - ')"></h3>
                    <br>
                    <div class="row">
                        <div class="col-md-6 text-left">Mínima:</div>
                        <div class="col-md-6 text-right"><h5 ng-bind="min[serie.id] | number:3"></h5></div><br>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-6 text-left">Máxima:</div>
                        <div class="col-md-6 text-right"><h5 ng-bind="max[serie.id] | number:3"></h5></div><br>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-6 text-left">Média:</div>
                        <div class="col-md-6 text-right"><h5 ng-bind="media[serie.id] | number:3"></h5></div><br>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-6 text-left">Ponderada:</div>
                        <div class="col-md-6 text-right"><h5 ng-bind="ponderada[serie.id] | number:3"></h5></div><br>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-6 text-left">Moda:</div>
                        <div class="col-md-6 text-right"><h5 ng-bind="moda[serie.id]"></h5></div><br>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-6 text-left">Mediana:</div>
                        <div class="col-md-6 text-right"><h5 ng-bind="mediana[serie.id] | number:3"></h5></div><br><br>
                    </div>
                </div>
            </div>

        </div>


    </div>
@endsection