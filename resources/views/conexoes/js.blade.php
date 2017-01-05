{{--<script src="/lib/jquery/jquery.min.js"></script>
<script src="/lib/bootstrap/js/bootstrap.min.js"></script>
<script src="/lib/angular/angular.min.js"></script>
<script src="/js/app.js"></script>--}}
<script src="/js/all.js"></script>
@if($rota=='contato')
    <script src="/js/controllers/contatoCtrl.js"></script>
    <script src="/lib/jquery/jquery.mask.min.js"></script>
    <script src="/js/directives/maskPhoneDir.js"></script>
    <script src="/lib/angular/angular-messages.min.js"></script>
@endif
@if($rota=='/')
    <script src="/js/controllers/linkCtrl.js"></script>
@endif
@if($rota=='renda')
<script>
    $(window).resize(function () {
    drawChartPi();
        drawChart();
    });
</script>

@endif
<script src="/js/directives/searchMenu.js"></script>
<script src="/js/controllers/serieCtrl.js"></script>
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
<script>
    var series = {};
    var valores = {};
    var dates = {};
    var string_dates = [];
    var valores_series = {};
    var series_por_id = {};
    var chart_dates = [];
    var chart_values = [];
    var cores_usadas = {};

    $.ajax({url: "/listar-series/", success: function(data){
        //console.log(data);
        series = data.series;
        valores = data.valores;

        for(var i in series){
            series_por_id[series[i].id] = '';
            series_por_id[series[i].id] = series[i].serie
        }
        //console.log(series_por_id);

        valores.forEach(function(valor){
            if(!dates[valor.data]){
                dates[valor.data] = [];
                string_dates.push(valor.data);
            }
            dates[valor.data][valor.id_serie] = valor;
            dates[valor.data]['date'] = valor.data;
            //console.log(valor);
        });
        //console.log(dates);
        //console.log(string_dates);
        string_dates.sort(function(a, b){
            return a - b;
        });
        //console.log('ajax', string_dates);
        //valuesToRange['date'] = string_dates;

        for(var i in dates){
            //console.log(dates[i]);
            for(var j in series){
                if(!valores_series[series[j].id]) {
                    valores_series[series[j].id] = [];
                }
                if(dates[i][series[j].id]){
                    //console.log(i, valores[j].id, dates[i][valores[j].id].valor);
                    valores_series[series[j].id].push(dates[i][series[j].id].valor);
                }else{
                    //console.log('');
                    valores_series[series[j].id].push(null);
                }
            }
        }

        chart_dates = string_dates;
        chart_values = jQuery.extend({}, valores_series);//cria um cópia do objeto na nova variavel ao invés de referenciar.


        /*http://ionden.com/a/plugins/ion.rangeSlider/demo_advanced.html*/
        $("#range").ionRangeSlider({

            values: string_dates,
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
                //console.log(data);

                loadChart(chart_values, chart_dates);

                //elementosIntervalo(data.from, data.to);

                angular.element('#serieCtrl').scope().intervalo(data.from_value, data.to_value);
                angular.element('#serieCtrl').scope().$apply();

                angular.element('#serieCtrl').scope().aplicarCores(cores_usadas);
                angular.element('#serieCtrl').scope().$apply();
            },
            onChange: function (data) {
                //console.log('onChange');
            },
            onFinish: function (data) {
                //console.log('onFinish');
                elementosIntervalo(data.from, data.to);

                loadChart(chart_values, chart_dates);

                angular.element('#serieCtrl').scope().aplicarCores(cores_usadas);
                angular.element('#serieCtrl').scope().$apply();

            },
            onUpdate: function (data) {
                //console.log('onUpdate');
            }

        });
    }});

    function loadChart(chart_values, chart_dates){
        var cores = ["104, 34, 139", "255, 193, 37", "30, 144, 255", "0, 191, 255", "70, 130, 180", "176, 196, 222", "173, 216, 230", "0, 255, 255", "102, 205, 170", "127, 255, 212",
            "143, 188, 143", "60, 179, 113", "124, 252, 0", "50, 205, 50", "255, 255, 0", "205, 205, 0", "255, 215, 0", "205, 173, 0", "205, 155, 29",
            "255, 64, 64", "238, 59, 59", "205, 51, 51", "139, 35, 35", "255, 0, 0", "205, 0, 0 ", "139, 0, 0", "255, 187, 255", "205, 150, 205", "224, 102, 255",
            "180, 82, 205", "191, 62, 255", "178, 58, 238", "135, 206, 235", "154, 50, 205", "155, 48, 255", "145, 44, 238", "255 165 000", "255 140 000", "255 127 080",
            "240, 128, 128", "255, 099, 071", "135, 206, 250", "255, 069, 000", "255, 239, 219", "238, 223, 204", "205, 192, 176", "139, 131, 120", "255, 228, 196", "238, 213, 183", "205, 183, 158"];
        var canvas = document.getElementById('myChart');

        var dataset = [];

        cores_usadas = {};

        var cont = 0;
        for(var i in chart_values){
            //var rand = Math.floor(Math.random() * 3) + 1
            var rgb1 = "rgba("+cores[cont]+",0.4)";
            var rgb2 = "rgba("+cores[cont]+",1)";
            cores_usadas[i] = cores[cont];
            delete cores[cont];
            dataset[cont] = {
                data: chart_values[i],
                /*data: valuesToChart2['value'],*/
                label: series_por_id[i],
                fill: false,
                lineTension: 0.1,
                backgroundColor: rgb1,
                borderColor: rgb2,
                borderCapStyle: 'butt',
                borderDash: [],
                borderDashOffset: 0.0,
                borderJoinStyle: 'miter',
                pointBorderColor: rgb2,
                pointBackgroundColor: "#fff",
                pointBorderWidth: 1,
                pointHoverRadius: 5,
                pointHoverBackgroundColor: rgb2,
                pointHoverBorderColor: rgb2,
                pointHoverBorderWidth: 2,
                pointRadius: 5,
                pointHitRadius: 10,
            };
            cont++;
        }

        var data = {
            labels: chart_dates,
            datasets: dataset
        };
        myLineChart = Chart.Line(canvas,{
            data:data,
            options:{
                showLines: true
            }
        });
    }


    function elementosIntervalo(start, end){
        myLineChart.destroy();

        var cont = 0;
        valuesToChart = [];
        chart_dates = [];

        for(var i in chart_values){
            chart_values[i] = [];

        }

        for(var i=start;i<=end;i++){
            chart_dates[cont] = string_dates[i];
            for(var id_serie in valores_series){
                chart_values[id_serie][cont] = valores_series[id_serie][i];
            }
            cont++;
        }


        angular.element('#serieCtrl').scope().filterDates(chart_dates, chart_values);
        angular.element('#serieCtrl').scope().$apply();
    }
</script>



@if($rota=='map')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.0.2/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.0.2/dist/leaflet.js"></script>
    <script>
        var map;
        autocomplete = [];

        $(document).ready(initialize);

        function initialize(){
            $("#map").height($(window).height());

            map = L.map("map", {
                center: L.latLng(-20, -45),
                zoom: 5
            });

            //var tileLayer = L.tileLayer("http://{s}.acetate.geoiq.com/tiles/acetate/{z}/{x}/{y}.png").addTo(map);
            var tileLayer = L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=pk.eyJ1IjoiYnJwYXNzb3MiLCJhIjoiY2l4N3l0bXF0MDFiczJ6cnNwODN3cHJidiJ9.qnfh8Jfn_be6gpo774j_nQ', {
                maxZoom: 18,
                attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, ' +
                '<a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
                'Imagery © <a href="http://mapbox.com">Mapbox</a>',
                id: 'mapbox.streets'
            }).addTo(map);




            //next: add features to map
            getData();
        };

        /*var mymap = L.map('mapid').setView([-16, -53], 4);
         L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=pk.eyJ1IjoiYnJwYXNzb3MiLCJhIjoiY2l4N3l0bXF0MDFiczJ6cnNwODN3cHJidiJ9.qnfh8Jfn_be6gpo774j_nQ', {
         attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="http://mapbox.com">Mapbox</a>',
         maxZoom: 18,
         id: 'mapbox.streets'
         }).addTo(mymap);*/

        function mapData(data){
            //remove existing map layers
            map.eachLayer(function(layer){
                //if not the tile layer
                if (typeof layer._url === "undefined"){
                    map.removeLayer(layer);
                }
            });

            //create geojson container object
            var geojson = {
                "type": "FeatureCollection",
                "features": []
            };

            //areas
            //mapArea('');

            //split data into features
            var dataArray = data.split(", ;");
            dataArray.pop();

            //console.log(dataArray);

            //build geojson features
            dataArray.forEach(function(d){
                d = d.split(", "); //split the data up into individual attribute values and the geometry

                //feature object container
                var feature = {
                    "type": "Feature",
                    "properties": {}, //properties object container
                    "geometry": JSON.parse(d[fields.length]) //parse geometry
                };

                for (var i=0; i<fields.length; i++){
                    feature.properties[fields[i]] = d[i];
                };

                //add feature names to autocomplete list
                if ($.inArray(feature.properties.featname, autocomplete) == -1){
                    autocomplete.push(feature.properties.featname);
                };

                geojson.features.push(feature);
            });

            //console.log(geojson);

            //activate autocomplete on featname input
            //$("input[name=featname]").autocomplete({
                //source: autocomplete
            //});

            var mapDataLayer = L.geoJson(geojson, {
                pointToLayer: function (feature, latlng) {
                    var markerStyle = {
                        fillColor: "#CC9900",
                        color: "#FFF",
                        fillOpacity: 0.5,
                        opacity: 0.8,
                        weight: 1,
                        radius: 8
                    };

                    return L.circleMarker(latlng, markerStyle);
                },
                onEachFeature: function (feature, layer) {
                    var html = "";
                    for (prop in feature.properties){
                        html += prop+": "+feature.properties[prop]+"<br>";
                    };
                    layer.bindPopup(html);
                }
            }).addTo(map);
        }

        function getData(){
            $.ajax("get-data", {
                data: {
                    table: "ed_territorios_uf"
                },
                success: function(data){
                    console.log(data);
                    mapData(data);
                }
            })
        }

        function getColor(d) {
            return d > 1000 ? '#800026' :
                d > 500  ? '#BD0026' :
                    d > 200  ? '#E31A1C' :
                        d > 100  ? '#FC4E2A' :
                            d > 50   ? '#FD8D3C' :
                                d > 20   ? '#FEB24C' :
                                    d > 10   ? '#FED976' :
                                        '#FFEDA0';
        }

        function style(feature) {
            return {
                fillColor: getColor(feature.properties.density),
                weight: 2,
                opacity: 1,
                color: 'white',
                dashArray: '3',
                fillOpacity: 0.7
            };
        }

    </script>
@endif