{{--<script src="/lib/jquery/jquery.min.js"></script>
<script src="/lib/bootstrap/js/bootstrap.min.js"></script>
<script src="/lib/angular/angular.min.js"></script>
<script src="/js/app.js"></script>--}}
<script src="js/all.js"></script>
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.0.2/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.0.2/dist/leaflet.js"></script>

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

@if($rota=='series')
    <script src="js/components/seriesList.js"></script>
@endif

@if($rota=='map/{id}/{titulo}')
    {{--http://www.chartjs.org/docs/--}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.1.3/Chart.min.js"></script>


    <script>
        indexLegend = 1;
        lastIndexLegend = 0;
        legend = [];
        cont = 0;
        intervalos = [];
    </script>
    <script src="js/components/map.js"></script>
    <script src="js/components/listValoresSeries.js"></script>
    <script src="js/components/rangePeriodos.js"></script>
    <script src="js/components/chartLine.js"></script>
    <script src="js/components/chartBar.js"></script>
    <script src="js/components/chartRadar.js"></script>
    <script src="js/components/chartPie.js"></script>
    <script src="js/components/regions.js"></script>
    <script src="js/components/calcs.js"></script>
    <script src="js/components/pgSerie.js"></script>


    <script>

        function gerarIntervalos(valores){
            let intervalos = [];
            let max = parseInt(valores[valores.length-1]);
            let maxUtil = parseInt(max - max * 10 / 100);
            let qtdIntervalos = 10;
            let intervalo = parseInt(maxUtil / qtdIntervalos);
            //console.log(intervalo);
            //console.log('resto', intervalo % 100);
            let rounder =  intervalo % 1000 > 100 ? 100 : intervalo % 100 > 10 ? 10 : 1;
            intervalo = Math.ceil(intervalo/rounder) * rounder;
            //console.log(intervalo);
            intervalos[0] = 0;
            intervalos[9] = maxUtil;
            for(let i=1;i<qtdIntervalos;i++){
                //intervalos[i] = intervalos[i-1] + intervalo;
                intervalos[i] = intervalos[i-1] + intervalo/qtdIntervalos*i;//intervalo/qtdIntervalos*i irá gerar um intervalo gradativo
            }
            return intervalos;
        }

        var colors = ['#FFEDA0', '#FED976', '#FEB24C', '#FD8D3C', '#FC4E2A', '#E31A1C',  '#BD0026',  '#9b0024',  '#800026',   '#5f0022'];

        function getColor(d) {
            var qtdIntervalos = intervalos.length;
            for(var i=qtdIntervalos-1; i>=0; i--){
                if(d > intervalos[i]){
                    //console.log(colors[i]);
                    return colors[i];
                }
            }
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
                    contadorIndices(0, '#contadorIndice1', data[0].valor);
                    contadorIndices(0, '#contadorIndice2', data[1].valor);
                    contadorIndices(0, '#contadorIndice3', data[2].valor);
                    contadorIndices(0, '#contadorIndice4', data[3].valor);

                    nomeIndices('#nomeIndice1', data[0].titulo);
                    nomeIndices('#nomeIndice2', data[1].titulo);
                    nomeIndices('#nomeIndice3', data[2].titulo);
                    nomeIndices('#nomeIndice4', data[3].titulo);
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