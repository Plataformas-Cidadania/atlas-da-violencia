<?php
    $setting = DB::table('settings')->orderBy('id', 'desc')->first();

    $series = \App\Serie::join('textos_series', 'series.id', '=', 'textos_series.serie_id')
        ->where('series.id', $setting->serie_id)
        ->first();
?>


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
{{--<link rel="stylesheet" href="https://unpkg.com/leaflet@1.0.2/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.0.2/dist/leaflet.js"></script>--}}

@if($rota=='contato')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.0.2/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.0.2/dist/leaflet.js"></script>
    <script src="js/controllers/contatoCtrl.js"></script>
    <script src="lib/jquery/jquery.mask.min.js"></script>
    <script src="js/directives/maskPhoneDir.js"></script>
    <script src="lib/angular/angular-messages.min.js"></script>
@endif
@if($rota=='/')
    <script src="js/controllers/linkCtrl.js"></script>

    <script src="js/chart/Chart.bundle.js"></script>
    <script src="js/chart/utils.js"></script>
    <script src="js/chart/chartAnimate.js"></script>

    <script>
        $.ajax("home-chart/<?php echo $setting->serie_id;?>", {
            data: {},
            success: function(data){
                //console.log(data);
                homeChart(data, '<?php echo $series->titulo;?>');
                ctx = document.getElementById("canvas").getContext("2d");
                window.myLine = new Chart(ctx, config);
                intervalo = window.setInterval('counterTime()', 570);
            },
            error: function(data){
                console.log('erro');
            }
        });

        $('.carousel').carousel({
            interval: 10000
        })

    </script>


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
{{--<script src="lib/numeral.js"></script>--}}
<script>
    // load a locale
    numeral.register('locale', 'br', {
        delimiters: {
            thousands: '.',
            decimal: ','
        },
        abbreviations: {
            thousand: 'k',
            million: 'm',
            billion: 'b',
            trillion: 't'
        },
        ordinal : function (number) {
            return number === 1 ? 'er' : 'ème';
        },
        currency: {
            symbol: '$R'
        }
    });

    // switch between locales
    numeral.locale('br');
</script>
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
    <script src="js/renders/renderSeries.js"></script>
@endif

@if($rota=='filtros/{id}/{titulo}')
    <script src="js/components/indicadores.js"></script>
    <script src="js/components/selectItems.js"></script>
    <script src="js/components/abrangencia.js"></script>
    <script src="js/components/seriesList.js"></script>
    <script src="js/components/rangePeriodos.js"></script>
    <script src="js/components/filtroRegioes2.js"></script>
    <script src="js/components/pgFiltros.js"></script>
@endif

@if($rota=='antigo-filtros-series/{id}/{tema}' || $rota=='antigo-filtros-series')
    <script src="js/components/filtros/subtemas.js"></script>
    <script src="js/components/filtros/temas.js"></script>
    <script src="js/components/filtros/indicadores.js"></script>
    <script src="js/components/filtros/selectItems.js"></script>
    <script src="js/components/filtros/abrangencia.js"></script>
    <script src="js/components/seriesList.js"></script>
    <script src="js/components/rangePeriodos.js"></script>
    <script src="js/components/filtros/filtroRegioes2.js"></script>
    <script src="js/components/filtros/pgFiltros.js"></script>
@endif

@if($rota=='filtros-series2/{id}/{tema}' || $rota=='filtros-series2')
    <script src="js/components/pagination.js"></script>
    <script src="js/components/filters/modal.js"></script>
    {{--<script src="js/components/filters/temas.js"></script>--}}
    <script src="js/components/filters/temasSelect.js"></script>
    <script src="js/components/filters/subtemas.js"></script>
    <script src="js/components/filters/filter.js"></script>
    <script src="js/components/filters/list.js"></script>
    <script src="js/components/selectItems.js"></script>
    <script src="js/components/filters/pageFilters.js"></script>
@endif

{{--@if($rota=='map/{id}/{titulo}')--}}
@if($rota=='dados-series/{serie_id}')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.0.2/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.0.2/dist/leaflet.js"></script>
    {{--http://www.chartjs.org/docs/--}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.1.3/Chart.min.js"></script>


    <script>
        indexLegend = 1;
        lastIndexLegend = 0;
        legend = [];
        cont = 0;
        intervalos = [];


        function gerarIntervalos2(valores){
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

        function gerarIntervalos(valores){
            let intervalos = [];

            let min = parseInt(valores[0]);
            let minUtil = parseInt(min + min * 10 / 100);

            let max = parseInt(valores[valores.length-1]);
            let maxUtil = parseInt(max - max * 10 / 100);

            let qtdIntervalos = 10;
            let intervalo = parseInt(maxUtil / qtdIntervalos);
            //console.log(intervalo);
            //console.log('resto', intervalo % 100);
            let rounder =  intervalo % 1000 > 100 ? 100 : intervalo % 100 > 10 ? 10 : 1;
            console.log('intervalo antes', intervalo);
            console.log('rounder', rounder);
            intervalo = Math.ceil(intervalo/rounder) * rounder;
            console.log('intervalo depois', intervalo);
            intervalos[0] = 0;
            intervalos[1] = minUtil;
            intervalos[9] = maxUtil;
            for(let i=2;i<qtdIntervalos;i++){
                //intervalos[i] = intervalos[i-1] + intervalo;
                intervalos[i] = intervalos[i-1] + intervalo/qtdIntervalos*i;//intervalo/qtdIntervalos*i irá gerar um intervalo gradativo
            }
            return intervalos;
        }

        //var colors = ['#F6D473', '#F3C46D', '#F1B567', '#EFA561', '#ED965B', '#EB8856',  '#E87850',  '#E66B4B',  '#E45A45',  '#E0433C'];
        var colors = ['#4285F4', '#689DF6', '#8EB6F8', '#B3CEFB', '#F6D473', '#F1B567', '#ED965B', '#E87850',  '#E45A45',  '#E0433C'];
        var colors2 = [
            '#008ECC',
            '#E13746',
            '#F29E19',
            '#008239',
            '#E96D38',
            '#D93984',
            '#005293',
            '#009839',
            '#502382',
            '#DDD203'
        ];



        function getColor(d, intervalos) {
            var qtdIntervalos = intervalos.length;
            for(var i=qtdIntervalos-1; i>=0; i--){
                if(d > intervalos[i]){
                    //console.log(colors[i]);
                    return colors[i];
                }
            }
        }

        /*function style(feature) {
            return {
                fillColor: getColor(feature.properties.total, intervalos),
                weight: 2,
                opacity: 1,
                color: 'white',
                dashArray: '3',
                fillOpacity: 0.9
            };
        }*/

    </script>




    <script src="js/components/print.js"></script>
    <script src="js/components/download.js"></script>
    <script src="js/components/map.js"></script>
    <script src="js/components/listValoresSeries.js"></script>
    <script src="js/components/selectItems.js"></script>
    <script src="js/components/filters/modal.js"></script>
    <script src="js/components/abrangenciaSerie.js"></script>
    <script src="js/components/rangePeriodos.js"></script>
    <script src="js/components/chartLine.js"></script>
    <script src="js/components/chartBar.js"></script>
    <script src="js/components/chartRadar.js"></script>
    <script src="js/components/chartPie.js"></script>
    <script src="js/components/regions.js"></script>
    <script src="js/components/calcs.js"></script>
    <script src="js/components/pgSerie.js"></script>
    <script src="lib/jquery/jquery.mask.min.js"></script>
    <script src="js/directives/maskPhoneDir.js"></script>
    <script src="js/controllers/contatoSerieCtrl.js"></script>


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

@if($rota=='filtros-dados-series/{tema_id}/{tema}' || $rota=='filtros-dados-series')
    <script src="js/components/dados/temas.js"></script>
    <script src="js/components/dados/subtemas.js"></script>
    <script src="js/components/dados/abrangencia.js"></script>
    <script src="js/components/dados/indicadores.js"></script>
    <script src="js/components/dados/series.js"></script>
    <script src="js/components/dados/filtros.js"></script>
    <script src="js/components/dados/pgSerie.js"></script>

@endif

@if($rota=='acidentes-transito')
    <link rel="stylesheet" href="lib/rslider/rSlider.min.css">
    {{--<script src="lib/rslider/rSlider.min.js"></script>--}}
    <script src="lib/rslider/rSlider.js"></script>


    <script src="lib/leaflet/js/leaflet-src.js"></script>

    <script src="lib/leaflet/js/leaflet.markercluster-src.js"></script>

    <script src="lib/leaflet/js/heatmap.js"></script>
    <script src="lib/leaflet/js/leaflet-heatmap-overlay.js"></script>

    <script src="lib/leaflet/js/Control.FullScreen.js"></script>

    <script src="js/components/pagination.js"></script>
    <script src="js/components/transito/range-year.js"></script>
    <script src="js/components/transito/range-month.js"></script>
    <script src="js/components/transito/territories.js"></script>
    <script src="js/components/transito/types.js"></script>
    <script src="js/components/transito/type.js"></script>
    <script src="js/components/transito/typeAccident.js"></script>
    <script src="js/components/transito/gender.js"></script>
    <script src="js/components/transito/region.js"></script>
    <script src="js/components/transito/map.js"></script>
    <script src="js/components/transito/chartBarHtml5.js"></script>
    <script src="js/components/transito/chartDonutHtml5.js"></script>
    <script src="js/components/transito/chartGender.js"></script>
    <script src="js/components/transito/listItems.js"></script>
    <script src="js/components/transito/filters.js"></script>
    <script src="js/components/transito/page.js"></script>
@endif

@if($rota=='filtros-series/{id}/{tema}' || $rota=='filtros-series')
    <script src="js/components/pagination.js"></script>
    <script src="js/components/filters/modal.js"></script>
    {{--<script src="js/components/filters/temas.js"></script>--}}
    <script src="js/components/filters/temasSelect.js"></script>
    <script src="js/components/filters/subtemas.js"></script>
    <script src="js/components/filters/filter.js"></script>
    <script src="js/components/filters/list.js"></script>
    <script src="js/components/selectItems.js"></script>
    <script src="js/components/filters/pageFilters.js"></script>
@endif