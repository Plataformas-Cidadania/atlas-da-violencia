<?php
$random = rand(123456789,987654321);
$setting = \App\Setting::first();
$colors = explode(',', $setting->cores_serie_home);
?>

<style>
    #chartBarPercent_{{$random}} {
        width: 100%;
        margin: 35px auto;
    }
</style>
<div id="chartBarPercent_{{$random}}"></div>
<script>

    var options = {
        series: [
                @foreach($element->content as $index => $dataset)
            {
                name:'{{$dataset[0]['name']}}',
                data: [
                    @foreach($dataset as $index => $dt)
                    {{$dt['y']}} @if($index < count($dataset)) , @endif
                    @endforeach
                ]
            },
            @endforeach
        ],
        title: {
            text: 'Título série'
        },
        chart: {
            type: 'bar',
            height: 350,
            stacked: true,
            stackType: '100%',
            toolbar: {
                show: true
            },
            zoom: {
                enabled: true
            }
        },
        responsive: [{
            breakpoint: 480,
            options: {
                legend: {
                    position: 'bottom',
                    offsetX: -10,
                    offsetY: 0
                }
            }
        }],
        plotOptions: {
            bar: {
                horizontal: false,
            },
        },
        colors: [
            @foreach($colors as $color)
                '{{$color}}',
            @endforeach
        ],
        dataLabels: {
            enabled: false,
            formatter: function (val) {
                return val + "%";
            },
        },
        yaxis: {
            labels: {
                formatter: function (y) {
                    return y.toFixed(0) + "%";
                }
            }
        },
        xaxis: {
            type: 'datetime',
            /*categories: ['01/01/2011 GMT', '01/02/2011 GMT', '01/03/2011 GMT', '01/04/2011 GMT',
                '01/05/2011 GMT', '01/06/2011 GMT'
            ],*/
            categories: [
                @foreach($element->content[0] as $index => $dt)
                    "{{$dt['x']}}" @if($index < count($element->content[0])) , @endif
                @endforeach
            ],
        },
        legend: {
            position: 'top',
            offsetY: 0
        },
        fill: {
            opacity: 1
        }
    };

    var chart<?php echo $random;?> = new ApexCharts(
        document.querySelector("#chartBarPercent_{{$random}}"),
        options
    );

    chart<?php echo $random;?>.render();

</script>
