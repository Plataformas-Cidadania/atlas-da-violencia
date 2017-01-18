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
{{--<script src="js/controllers/serieCtrl.js"></script>--}}
<script src="lib/react/react.js"></script>
<script src="lib/react/react-dom.js"></script>
<script src="lib/numeral.js"></script>
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



    <script src="js/components/listValoresSeries.js"></script>
    <script src="js/components/rangePeriodos.js"></script>
    <script src="js/components/pgSerie.js"></script>


    <script>

        var periodos = [];


        function dataToMap(min, max){

            $.ajax("regiao/"+min+"/"+max, {
                data: {},
                success: function(data){
                    console.log(data);
                    console.log(':::::::::::::::::::::::::::::::');
                    loadMap(data);
                },
                error: function(data){
                    console.log('erro');
                }
            })
        }

        var indexLegend = 1;
        var lastIndexLegend = 0;
        var legend = [];
        var cont = 0;
        var intervalos = [];
        function loadMap(data){

            //remove existing map layers
            mymap.eachLayer(function(layer){
                //if not the tile layer
                if (typeof layer._url === "undefined"){
                    mymap.removeLayer(layer);
                }
            });

            let valores = [];
            for(let i in data.features){
                valores[i] = data.features[i].properties.total;
            }
            console.log(valores);

            let max = parseInt(valores[valores.length-1]);
            let maxUtil = parseInt(max - max * 10 / 100);
            let qtdIntervalos = 10;
            let intervalo = parseInt(maxUtil / qtdIntervalos);
            console.log(intervalo);
            console.log('resto', intervalo % 100);
            var arredondador =  intervalo % 1000 > 100 ? 100 : intervalo % 100 > 10 ? 10 : 1;
            intervalo = Math.ceil(intervalo/arredondador) * arredondador;
            console.log(intervalo);
            intervalos[0] = 0;
            intervalos[9] = maxUtil;
            for(let i=1;i<qtdIntervalos;i++){
                intervalos[i] = intervalos[i-1] + intervalo;
            }
            //console.log(intervalos);

            geojson = L.geoJson(data, {
                style: style,
                onEachFeature: onEachFeature //listeners
            }).addTo(mymap);

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
                let div = L.DomUtil.create('div', 'info legend'),
                    //grades = [0, 100, 300, 600, 1000, 1500, 3000, 5000, 7000, 9000],
                    grades = intervalos,
                    labels = [];
                // loop through our density intervals and generate a label with a colored square for each interval
                for (let i = 0; i < grades.length; i++) {
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

            let polygon2 = L.polygon([
                [51.509, -0.08],
                [51.503, -0.06],
                [51.51, -0.047]
            ]).addTo(mymap);



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

        /////////////////////MOUSE OVER LEGEND///////////////////////////
        function highlightFeature(e) {
            var layer = e.target;
            layer.setStyle({
                weight: 5,
                color: '#333',
                dashArray: '',
                fillOpacity: 0.7
            });

            if (!L.Browser.ie && !L.Browser.opera && !L.Browser.edge) {
                layer.bringToFront();
            }

            info.update(layer.feature.properties);
        }
        function resetHighlight(e) {
            geojson.resetStyle(e.target);
            info.update();
        }
        function zoomToFeature(e) {
            mymap.fitBounds(e.target.getBounds());
        }

        var geojson;
        // ... our listeners
        function onEachFeature(feature, layer) {
            layer.on({
                mouseover: highlightFeature,
                mouseout: resetHighlight,
                click: zoomToFeature
            });
        }

        /*geojson = L.geoJson(statesData, {
            style: style,
            onEachFeature: onEachFeature
        }).addTo(map);*/

        var info = L.control();

        info.onAdd = function (mymap) {
            this._div = L.DomUtil.create('div', 'info'); // create a div with a class "info"
            this.update();
            return this._div;
        };

        // method that we will use to update the control based on feature properties passed
        info.update = function (props) {
            this._div.innerHTML =
                '<h4>Ocorrências</h4>' +  (props ? '<b>' + props.uf + '</b><br />' + props.total
                    : 'Passe o mouse na região');
        };

        info.addTo(mymap);

        /////////////////////////////////////////////////////////////////


        var colors = ['#FFEDA0', '#FED976', '#FEB24C', '#FD8D3C', '#FC4E2A', '#E31A1C',  '#BD0026',  '#9b0024',  '#800026',   '#5f0022'];

        function getColor(d) {

            var qtdIntervalos = intervalos.length;
            for(var i=qtdIntervalos-1; i>=0; i--){
                if(d > intervalos[i]){
                    return colors[i];
                }
            }

            /*return  d > 9000 ? '#5f0022' :
                    d > 7000  ? '#800026' :
                    d > 5000  ? '#9b0024' :
                    d > 3000  ? '#BD0026' :
                    d > 1500  ? '#E31A1C' :
                    d > 1000  ? '#FC4E2A' :
                    d > 600   ? '#FD8D3C' :
                    d > 300   ? '#FEB24C' :
                    d > 100   ? '#FED976' :
                    '#FFEDA0';*/
        }

        function style(feature) {
            return {
                fillColor: getColor(feature.properties.total),
                weight: 2,
                opacity: 1,
                color: 'white',
                dashArray: '3',
                fillOpacity: 0.5
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
            $.ajax("valores-regiao/"+min+"/"+max, {
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