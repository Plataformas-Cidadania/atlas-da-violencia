<?php
    $random = rand(123456789,987654321);
    $setting = \App\Setting::first();
    $colors = explode(',', $setting->cores_serie_home);
?>

<style>
    #chartArea_{{$random}} {
        width: 100%;
        margin: 35px auto;
    }
</style>
<div id="chartNegative_{{$random}}"></div>
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
        chart: {
            type: 'bar',
            height: 440,
            stacked: true
        },
        colors: [
            @foreach($colors as $color)
                '{{$color}}',
            @endforeach
                '#D50000','#C51162','#AA00FF','#6200EA','#00B8D4','#00BFA5','#00C853',
        ],
        plotOptions: {
            bar: {
                horizontal: true,
                barHeight: '80%',

            },
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            width: 1,
            colors: ["#fff"]
        },

        grid: {
            xaxis: {
                lines: {
                    show: false
                }
            }
        },
        yaxis: {
            min: -5,
            max: 5,
            title: {
                // text: 'Age',
            },
        },
        tooltip: {
            shared: false,
            x: {
                formatter: function (val) {
                    return val
                }
            },
            y: {
                formatter: function (val) {
                    return Math.abs(val) + "%"
                }
            }
        },
        title: {
            text: 'Mauritius population pyramid 2011'
        },
        xaxis: {
            categories: [
                @foreach($element->content[0] as $index => $dt)
                    "{{$dt['x']}}" @if($index < count($element->content[0])) , @endif
                @endforeach
            ],
            title: {
                text: 'Percent'
            },
            labels: {
                formatter: function (val) {
                    return Math.abs(Math.round(val)) + "%"
                }
            }
        },
    };

    /*var chart = new ApexCharts(document.querySelector("#chart"), options);
    chart.render();*/

    var chart<?php echo $random;?> = new ApexCharts(
        document.querySelector("#chartNegative_{{$random}}"),
        options
    );
    chart<?php echo $random;?>.render();



</script>
