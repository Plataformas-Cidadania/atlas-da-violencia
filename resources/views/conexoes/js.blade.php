<?php
$setting = DB::table('settings')->orderBy('id', 'desc')->first();
$lang =  App::getLocale();
$series = \App\Serie::join('textos_series', 'series.id', '=', 'textos_series.serie_id')
    ->where('series.id', $setting->serie_id)
    ->where('textos_series.idioma_sigla', $lang)
    ->first();
?>

<script src="js/all.js"></script>
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>


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

    @if(!empty($series))
    <script>
        $.ajax("home-chart/<?php echo $setting->serie_id;?>", {
            data: {},
            success: function(data){
                console.log(data);
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
    @endif


    {
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
<script src="lib/react/react.js"></script>
<script src="lib/react/react-dom.js"></script>

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

    numeral.locale('br');
</script>


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
    <script src="js/components/filters/temasSelect.js"></script>
    <script src="js/components/filters/subtemas.js"></script>
    <script src="js/components/filters/filter.js"></script>
    <script src="js/components/filters/list.js"></script>
    <script src="js/components/selectItems.js"></script>
    <script src="js/components/filters/pageFilters.js"></script>
@endif

@if($rota=='dados-series/{serie_id}')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.0.2/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.0.2/dist/leaflet.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.1.3/Chart.min.js"></script>

    {{--<script src="https://www.chartjs.org/dist/2.7.3/Chart.bundle.js"></script>--}}


    <script>
        indexLegend = 1;
        lastIndexLegend = 0;
        legend = [];
        cont = 0;
        intervalos = [];

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

@if($rota=='dados-series-comparadas/{ids}')
    <script>
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
    </script>
    <script src="js/components/print.js"></script>
    <script src="js/components/download.js"></script>
    <script src="js/components/selectItems.js"></script>
    <script src="js/components/filters/modal.js"></script>
    <script src="js/components/abrangenciaSerie.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.1.3/Chart.min.js"></script>
    <script src="js/components/compareted-data/regions.js"></script>
    <script src="js/components/compareted-data/chartLineComparatedSeries.js"></script>
    <script src="js/components/compareted-data/listValoresComparatedSeries.js"></script>
    <script src="js/components/compareted-data/pageComparatedData.js"></script>
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

                    for(var i=0;i<data.length;i++){
                        contadorIndices(0, '#contadorIndice'+(i+1), data[i].valor);
                    }

                    for(var i=0;i<data.length;i++){
                        nomeIndices('#nomeIndice'+(i+1), data[i].titulo);
                    }

                    /*contadorIndices(0, '#contadorIndice1', data[0].valor);
                    contadorIndices(0, '#contadorIndice2', data[1].valor);
                    contadorIndices(0, '#contadorIndice3', data[2].valor);
                    contadorIndices(0, '#contadorIndice4', data[3].valor);

                    nomeIndices('#nomeIndice1', data[0].titulo);
                    nomeIndices('#nomeIndice2', data[1].titulo);
                    nomeIndices('#nomeIndice3', data[2].titulo);
                    nomeIndices('#nomeIndice4', data[3].titulo);*/
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
            console.log(id, text);
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

@if($rota=='pontos/{serie_id}')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.0.2/dist/leaflet.css" />
    <link rel="stylesheet" href="lib/rslider/rSlider.min.css">
    <script src="lib/rslider/rSlider.js"></script>


    <script src="lib/leaflet/js/leaflet-src.js"></script>
    <script src="lib/leaflet/js/leaflet.markercluster-src.js"></script>
    <script src="lib/leaflet/js/heatmap.js"></script>
    <script src="lib/leaflet/js/leaflet-heatmap-overlay.js"></script>
    <script src="lib/leaflet/js/leaflet-heat.js"></script>
    <script src="lib/leaflet/js/Control.FullScreen.js"></script>

    <script src="js/components/pagination.js"></script>
    <script src="js/components/pontos/range-year.js"></script>
    <script src="js/components/pontos/range-month.js"></script>
    <script src="js/components/pontos/territories.js"></script>
    <script src="js/components/pontos/types.js"></script>
    <script src="js/components/pontos/type.js"></script>
    <script src="js/components/pontos/typeAccident.js"></script>
    <script src="js/components/pontos/gender.js"></script>
    <script src="js/components/pontos/region.js"></script>
    <script src="js/components/pontos/map.js"></script>
    <script src="js/components/pontos/chartBarHtml5.js"></script>
    <script src="js/components/pontos/chartDonutHtml5.js"></script>
    <script src="js/components/pontos/chartGender.js"></script>
    <script src="js/components/pontos/listItems.js"></script>
    <script src="js/components/pontos/filter.js"></script>
    <script src="js/components/pontos/filters.js"></script>
    <script src="js/components/cards.js"></script>
    <script src="js/components/pontos/page.js"></script>
@endif

@if($rota=='filtros-series/{id}/{tema}' || $rota=='filtros-series')
    <script src="js/components/pagination.js"></script>
    <script src="js/components/filters/modal.js"></script>
    <script src="js/components/filters/temasSelect.js"></script>
    <script src="js/components/filters/subtemas.js"></script>
    <script src="js/components/filters/filter.js"></script>
    <script src="js/components/filters/list.js"></script>
    <script src="js/components/selectItems.js"></script>
    <script src="js/components/filters/pageFilters.js"></script>
@endif

@if($rota=='filtros-comparar-series/{id}/{tema}' || $rota=='filtros-comparar-series')
    <script src="js/components/pagination.js"></script>
    <script src="js/components/filters-compare-series/modal.js"></script>
    <script src="js/components/filters-compare-series/temasSelect.js"></script>
    <script src="js/components/filters-compare-series/subtemas.js"></script>
    <script src="js/components/filters-compare-series/filter.js"></script>
    <script src="js/components/filters-compare-series/list.js"></script>
    <script src="js/components/selectItems.js"></script>
    <script src="js/components/filters-compare-series/pageFilters.js"></script>
@endif
@if($rota=='consultas')
    <script src="js/components/pagination.js"></script>
    {{--<script src="js/components/filters/modal.js"></script>--}}
    <script src="js/components/filters/temasSelect.js"></script>
    <script src="js/components/filters/subtemas.js"></script>
    <script src="js/components/filters/filter.js"></script>
    <script src="js/components/filters/list.js"></script>
    {{--<script src="js/components/selectItems.js"></script>--}}
    <script src="js/components/consultas/pageConsultas.js"></script>
@endif
