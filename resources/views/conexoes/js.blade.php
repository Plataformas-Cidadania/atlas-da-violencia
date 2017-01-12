{{--<script src="/lib/jquery/jquery.min.js"></script>
<script src="/lib/bootstrap/js/bootstrap.min.js"></script>
<script src="/lib/angular/angular.min.js"></script>
<script src="/js/app.js"></script>--}}
<script src="js/all.js"></script>
@if($rota=='contato')
    <script src="js/controllers/contatoCtrl.js"></script>
    <script src="lib/jquery/jquery.mask.min.js"></script>
    <script src="js/directives/maskPhoneDir.js"></script>
    <script src="lib/angular/angular-messages.min.js"></script>
@endif
@if($rota=='/')
    <script src="js/controllers/linkCtrl.js"></script>
    {{--<script>$('.block').smoove({offset:'10%'});</script>--}}
@endif
@if($rota=='renda')
    <script>
        $(window).resize(function () {
        drawChartPi();
            drawChart();
        });
    </script>
@endif
<script src="js/directives/searchMenu.js"></script>
<script src="js/controllers/serieCtrl.js"></script>
<?php

/*// Datas de início e fim
$dataInicio = DateTime::createFromFormat("d/m/Y", "01/01/2000");
$dataFim = DateTime::createFromFormat("d/m/Y", "01/01/2016");

// Intervalo de 1 dia
$intervalo_dias = new DateInterval('P1D');
$intervalo_meses = new DateInterval('P1M');

// (Array) Período em dias, incluindo datas de inicio e fim
$periodo_dias = new DatePeriod($dataInicio, $intervalo_dias, $dataFim->add($intervalo_dias));
$periodo_meses = new DatePeriod($dataInicio, $intervalo_meses, $dataFim->add($intervalo_meses));

//foreach ($periodo_dias as $dia) {
//    echo $dia->format("d/m/Y");
//    echo "<br>";
//}

$cont = 0;
foreach($periodo_meses as $mes){
    $periodo[$cont] = $mes->format("d/m/Y");
    $cont++;
}

$qtd = count($periodo);
$limite = 50;
$divisor = $qtd/$limite;
if($qtd < $limite){
    $divisor = 1;
}

$cont = 0;
for($i=0;$i<$qtd;$i+=$divisor){
    $periodo_limite[$cont] = $periodo[$i];
    $cont++;
}

echo 'qtd total: '.$qtd.'<br>';
echo 'divisor: '.$divisor.'<br>';
echo 'qtd selecionado: '.count($periodo_limite).'<br>';
print_r($periodo_limite);*/

?>



@if($rota=='map')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.1.3/Chart.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.0.2/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.0.2/dist/leaflet.js"></script>
    <script>

        var periodos = [];

        $( document ).ready( function() {
            dataToRange()

        });

        function dataToRange(){
            $.ajax("periodos", {
                data: {},
                success: function(data){
                    //console.log(data);
                    periodos = data;
                    loadRange();
                },
                error: function(data){
                    console.log('erro');
                }
            })
        }

        function loadRange(){
            $("#range").ionRangeSlider({
                values: periodos,
                hide_min_max: true,
                keyboard: true,
                /*min: 0,
                 max: 5000,
                 from: 1000,
                 to: 4000,*/
                type: 'double',
                step: 1,
                prefix: "",
                //postfix: " million pounds",
                grid: true,
                prettify_enabled: false,
                onStart: function (data) {
                    dataToMap(data.from_value, data.to_value);
                    dataToChart(data.from_value, data.to_value);
                    dataToChartRadar(data.from_value, data.to_value);
                },
                onChange: function (data) {
                    //console.log('onChange');
                },
                onFinish: function (data) {
                    dataToMap(data.from_value, data.to_value);
                    clearCharts();
                    dataToChart(data.from_value, data.to_value);
                    dataToChartRadar(data.from_value, data.to_value);
                },
                onUpdate: function (data) {
                    //console.log('onUpdate');
                }

            });
        }

        function dataToMap(min, max){
            $.ajax("regiao/"+min+"/"+max, {
                data: {},
                success: function(data){
                    //console.log(data);
                    loadMap(data);
                },
                error: function(data){
                    console.log('erro');
                }
            })
        }

        ///////////////////////////////////////////////////////////////////////////////
        var mymap = L.map('mapid').setView([-10, -52], 4);
        var tileLayer = L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=pk.eyJ1IjoiYnJwYXNzb3MiLCJhIjoiY2l4N3l0bXF0MDFiczJ6cnNwODN3cHJidiJ9.qnfh8Jfn_be6gpo774j_nQ', {
            maxZoom: 18,
            attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, ' +
            '<a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
            'Imagery © <a href="http://mapbox.com">Mapbox</a>',
            id: 'mapbox.streets'
        }).addTo(mymap);
        ///////////////////////////////////////////////////////////////////////////////


        var indexLegend = 1;
        var lastIndexLegend = 0;
        var legend = [];
        function loadMap(data){

            var valores = [];


            //remove existing map layers
            mymap.eachLayer(function(layer){
                //if not the tile layer
                if (typeof layer._url === "undefined"){
                    mymap.removeLayer(layer);
                }
            });

            var regiao = [];

            //for(var i=0; i<data.length; i++){
            for(var i in data){
                valores[i] = data[i].total;
                regiao[i] = JSON.parse(data[i].st_asgeojson);
                L.geoJson(regiao[i], {style: style(data[i].total)}).addTo(mymap);
            }

            console.log(valores);

            var min = valores[0];
            var max = valores[valores.length-1];

            console.log(min, max);

            var intervalos = [];
            var qtdIntervalos = 10;
            intervalos[0] = 0;
            intervalos[9] = max;

            console.log(min%10);

            for(var i=1;i<qtdIntervalos;i++){

            }

            /*for(var i=0; i<data.circles.length; i++){
                var circle = L.circle([data.circles[i].st_y, data.circles[i].st_x], {
                    color: 'red',
                    fillColor: '#f03',
                    fillOpacity: 0.5,
                    radius: data.circles[i].valor*10
                }).addTo(mymap);
            }*/

            legend[indexLegend] = L.control({position: 'bottomright'});


            legend[indexLegend].onAdd = function (mymap) {
                var div = L.DomUtil.create('div', 'info legend'),
                    grades = [0, 100, 300, 600, 1000, 1500, 3000, 5000, 7000, 9000],
                    labels = [];
                // loop through our density intervals and generate a label with a colored square for each interval
                for (var i = 0; i < grades.length; i++) {
                    div.innerHTML +=
                        '<i style="background:' + getColor(grades[i] + 1) + '"></i> ' +
                        grades[i] + (grades[i + 1] ? '&ndash;' + grades[i + 1] + '<br>' : '+');
                }
                return div;
            };

            if(lastIndexLegend!=0){
                mymap.removeControl(legend[lastIndexLegend]);
            }
            legend[indexLegend].addTo(mymap);
            lastIndexLegend = indexLegend;
            indexLegend++;

            /*for(var i in data){
                var circle = L.circle([data[i].st_y, data[i].st_x], {
                    color: 'red',
                    fillColor: '#f03',
                    fillOpacity: 0.5,
                    radius: 50000
                }).addTo(mymap);
            }*/

            var polygon2 = L.polygon([
                [51.509, -0.08],
                [51.503, -0.06],
                [51.51, -0.047]
            ]).addTo(mymap);
        }

        function getColor(d) {
            return  d > 9000 ? '#5f0022' :
                    d > 7000  ? '#800026' :
                    d > 5000  ? '#9b0024' :
                    d > 3000  ? '#BD0026' :
                    d > 1500  ? '#E31A1C' :
                    d > 1000  ? '#FC4E2A' :
                    d > 600   ? '#FD8D3C' :
                    d > 300   ? '#FEB24C' :
                    d > 100   ? '#FED976' :
                    '#FFEDA0';
        }

        function style(feature) {
            return {
                fillColor: getColor(feature),
                weight: 2,
                opacity: 1,
                color: 'white',
                dashArray: '3',
                fillOpacity: 0.7
            };
        }

        function dataToChart(min, max){
            $.ajax("periodo/"+min+"/"+max, {
                data: {},
                success: function(data){
                    //console.log(data);
                    loadChart(data);
                },
                error: function(data){
                    console.log('erro');
                }
            })
        }


        function loadChart(data){
            var labels = [];
            var values = [];
            for(var i in data){
                labels[i] = data[i].periodo;
                values[i] = data[i].total;
            }

            var canvas = document.getElementById('myChart');
            var dataChart = {
                //labels: ["January", "February", "March", "April", "May", "June", "July"],
                labels: labels,
                datasets: [
                    {
                        label: "",
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
                        data: values,
                    }
                ]
            };

            var option = {
                showLines: true
            };
            myLineChart = Chart.Line(canvas,{
                data:dataChart,
                options:option
            });


        }

        function dataToChartRadar(min, max){
            $.ajax("regiao/"+min+"/"+max, {
                data: {},
                success: function(data){
                    //console.log(data);
                    loadChartRadar(data);
                },
                error: function(data){
                    console.log('erro');
                }
            })
        }

        function loadChartRadar(data){
            //console.log(data);
            var labels = [];
            var values = [];
            for(var i in data){
                labels[i] = data[i].uf;
                values[i] = data[i].total;
            }

            //console.log(values);

            var canvas2 = document.getElementById('myChartRadar');
            var dataChart = {
                labels: labels,
                datasets: [
                    {
                        label: "Homicidios no Brasil",
                        backgroundColor: "rgba(179,181,198,0.2)",
                        borderColor: "rgba(179,181,198,1)",
                        pointBackgroundColor: "rgba(179,181,198,1)",
                        pointBorderColor: "#fff",
                        pointHoverBackgroundColor: "#fff",
                        pointHoverBorderColor: "rgba(179,181,198,1)",
                        data: values
                    }
                ]
            };

            var options2 = {
                scale: {
                    reverse: false,
                    ticks: {
                        beginAtZero: true
                    }
                }
            };


            myRadarChart = new Chart(canvas2, {
                type: 'radar',
                data: dataChart,
                options: options2
            });

        }

        function clearCharts(){
            myLineChart.destroy();
            myRadarChart.destroy();
        }


    </script>


@endif


@if($rota=="/")
    <script>
        $( document ).ready(function() {
            getIndices();
        });

        function getIndices(){
            $.ajax("indices", {
                data: {},
                success: function(data){
                    //console.log(data);
                    contadorIndices(0, '#contadorIndice1', data[0]);
                    contadorIndices(0, '#contadorIndice2', data[1]);
                    contadorIndices(0, '#contadorIndice3', data[2]);
                    contadorIndices(0, '#contadorIndice4', data[3]);

                    nomeIndices('#nomeIndice1', 'Furtos');
                    nomeIndices('#nomeIndice2', 'Juventude Perdida');
                    nomeIndices('#nomeIndice3', 'Homicídios');
                    nomeIndices('#nomeIndice4', 'Violência de Gênero');
                },
                error: function(data){
                    console.log('erro');
                }
            })
        }


        function contadorIndices(i, id, total) {
            setTimeout(function () {
                i+=Math.ceil(total/300);
                if (i <= total) {
                    contadorIndices(i, id, total);
                }
                if(i>total){
                    i=total;
                }
                $(id).html(i);
            }, 5)
        }

        function nomeIndices(id, text) {
            $(id).html(text);
        }


        var i = 0;

        function myLoop () {
            setTimeout(function () {
                i+=32;
                if (i <= totalCount) {
                    myLoop();
                }
                if(i>totalCount){
                    i=totalCount;
                }
                $('#contador').html(i);
            }, 5)
        }
        //myLoop();
    </script>
@endif