@extends('layout')
@section('title', "Renda")
@section('content')

    <div class="container">
        <h2 id="calendar" aria-label=", ">Renda mensal</h2>
        <div class="line_title bg-pri"></div>
        <div class="row" ng-init="adultos=1; criancas=0;">
            <div class="col-md-4">
                <div class="input-group">
                    <div class="input-group-addon">R$</div>
                    <input type="text" ng-model="renda" ng-required="true" class="form-control input-lg" placeholder="* Renda mensal" >
                    <div class="input-group-addon">,00</div>
                </div>
                <br>
            </div>
            <div class="col-md-4">
                <div class="input-group">
                    <input type="number" ng-model="adultos" ng-required="true" class="form-control input-lg" min="0" >
                    <div class="input-group-addon">Adultos</div>
                </div>
                <br>
            </div>
            <div class="col-md-4">
                <div class="input-group">
                    <input type="number" ng-model="criancas" ng-required="true" class="form-control input-lg" min="0" >
                    <div class="input-group-addon">Crianças</div>
                </div>
                <br>
            </div>
        </div>
        <div class="row">
            <br><br>
            <div class="col-md-12 bg-pri">
                <h2 class="color-sec">Sua renda familiar é de: <strong ng-bind="rendaFamiliar =  renda / (adultos + (criancas - (criancas*0.7))) | currency: 'R$ '"></strong></h2><br>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12"><h2 class="h1_title text-center"><br><br>Eu estou na classe</h2></div>

            <div class="col-md-12">
                <br><br><br><br><br>
                <ul class="chart">
                    <li>
                        <span style="height:2.3%;" ng-class="{'bg-pri': rendaFamiliar <= 227}"><strong class="hidden-xs">R$ 227,00</strong></span>
                        <div class="hidden-xs hidden-sm">Extremamente pobre</div>
                        <div class="hidden-md hidden-lg">E</div>
                    </li>
                    <li>
                        <span style="height:6.5%;" ng-class="{'bg-pri': rendaFamiliar > 227 && rendaFamiliar <= 648}"><strong class="hidden-xs">R$ 648,00</strong></span>
                        <div class="hidden-xs hidden-sm">Pobre</div>
                        <div class="hidden-md hidden-lg">D</div>
                    </li>
                    <li>
                        <span style="height:10.3%" title="" ng-class="{'bg-pri': rendaFamiliar > 648 && rendaFamiliar <= 1030}"><strong class="hidden-xs">R$ 1.030,00</strong></span>
                        <div class="hidden-xs hidden-sm">Vulneravel</div>
                        <div class="hidden-md hidden-lg">C2</div>
                    </li>
                    <li>
                        <span style="height:15.4%" ng-class="{'bg-pri': rendaFamiliar > 1030 && rendaFamiliar <= 1540}"><strong class="hidden-xs">R$ 1.540,00</strong></span>
                        <div class="hidden-xs hidden-sm">Baixa Classe Média</div>
                        <div class="hidden-md hidden-lg">C1</div>
                    </li>
                    <li>
                        <span style="height:19.2%"  ng-class="{'bg-pri': rendaFamiliar > 1540 && rendaFamiliar <= 1925}"><strong class="hidden-xs">R$ 1.925,00</strong></span>
                        <div class="hidden-xs hidden-sm">Média Classe Média1</div>
                        <div class="hidden-md hidden-lg">B2</div>
                    </li>
                    <li>
                        <span style="height:28%"  ng-class="{'bg-pri': rendaFamiliar > 1925 && rendaFamiliar <= 2813}"><strong class="hidden-xs">R$ 2.813,00</strong></span>
                        <div class="hidden-xs hidden-sm">Alta Classe Média</div>
                        <div class="hidden-md hidden-lg">B1</div>
                    </li>
                    <li>
                        <span style="height:48%"  ng-class="{'bg-pri': rendaFamiliar > 2813 && rendaFamiliar <= 4845}"><strong class="hidden-xs">R$ 4.845,00</strong></span>
                        <div class="hidden-xs hidden-sm">Baixa Classe Alta</div>
                        <div class="hidden-md hidden-lg">A2</div>
                    </li>
                    <li>
                        <span style="height:130%" ng-class="{'bg-pri': rendaFamiliar > 4845 && rendaFamiliar <= 12988}"><strong class="hidden-xs">R$ 12.988,00</strong></span>
                        <div class="hidden-xs hidden-sm">Alta Classe Alta</div>
                        <div class="hidden-md hidden-lg">A1</div>
                    </li>
                </ul>
                <br><br><br>
                <div class="hidden-md hidden-lg">
                    <table class="table  table-hover">
                        <thead>
                        <tr>
                            <th>CL</th>
                            <th>Classe</th>
                            <th>Valor</th>
                        </tr>
                        </thead>
                        <tr>
                            <td>A1</td>
                            <td>Alta Classe Alta</td>
                            <td>R$ 12.988,00</td>
                        </tr>
                        <tr>
                            <td>A2</td>
                            <td>Baixa Classe Alta</td>
                            <td>R$ 4.845,00</td>
                        </tr>
                        <tr>
                            <td>B1</td>
                            <td>Alta Classe Média</td>
                            <td>R$ 2.813,00</td>
                        </tr>
                        <tr>
                            <td>B2</td>
                            <td>Média Classe Média1</td>
                            <td>R$ 1.925,00</td>
                        </tr>
                        <tr>
                            <td>C1</td>
                            <td>Baixa Classe Média</td>
                            <td>R$ 1.540,00</td>
                        </tr>
                        <tr>
                            <td>C2</td>
                            <td>Vulneravel</td>
                            <td>R$ 1.030,00</td>
                        </tr>
                        <tr>
                            <td>D</td>
                            <td>Pobre</td>
                            <td>R$ 648,00</td>
                        </tr>
                        <tr>
                            <td>E</td>
                            <td>Extremamente pobre</td>
                            <td>R$ 227,00</td>
                        </tr>
                    </table>

                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12"><h2 class="h1_title text-center"><br><br>Você é mais rico do que 39,6% da população Brasileira</h2><br><br></div>
            <div class="row">
                <div class="col-md-4">
                    <div id="piechart"></div>
                </div>
                <div class="col-md-8">
                    <br><br><br>
                    <p><i class="fa fa-male fa-2x" aria-hidden="true"></i>&ensp; Existem 41 milhões de pessoas que ganham mais do que você no Brasil, o que represanta 37% da população com rendimento</p>
                    <br>
                    <p><i class="fa fa-male fa-2x" aria-hidden="true"></i>&ensp; Existem 70 milhões de pessoas que ganham menos do que você no Brasil, o que represanta 37% da população com rendimento</p>
                </div>
            </div>
        </div>


    </div>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script>


        google.charts.load("current", {packages:["corechart"]});
        google.charts.setOnLoadCallback(drawChartPi);
        function drawChartPi() {
            var data = google.visualization.arrayToDataTable([
                ['Language', 'Speakers (in millions)'],
                ['Mais ricos',  5.85],
                ['Minha posição',  4.66],
                ['Mais pobres', 3.791]
            ]);

            var options = {
                legend: 'none',
                pieSliceText: 'label',
                pieStartAngle: 100,
            };

            var chart2 = new google.visualization.PieChart(document.getElementById('piechart'));
            chart2.draw(data, options);
        }
        /////////////////////////////////////////////

        /* ["Classe Element", "Density", { role: "style" } ],
         ["Extremamente pobre", 227, "#CCCCCC"],
         ["pobre", 648, "#CCCCCC"],
         ["Vulneravel C", 1030, "#CCCCCC"],
         ["Baixa Classe Média", 1540, "#CCCCCC"],
         ["Média Classe Média1", 1925, "#89C71C"],
         ["Alta Classe Média", 2813, "#CCCCCC"],
         ["Baixa Classe Alta", 4845, "color: #CCCCCC"],
         ["Alta Classe Alta", 12988, "color: #CCCCCC"]*/



    </script>
@endsection

