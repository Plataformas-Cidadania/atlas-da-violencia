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
<div id="chartArea_{{$random}}"></div>
<script>
    var options = {
        series: [
            @foreach($element->content as $index => $dataset)
            {
                name:'{{$dataset[0]['name']}}',
                type: 'area',
                data: [
                    @foreach($dataset as $index => $dt)
                    {{$dt['y']}} @if($index < count($dataset)) , @endif
                    @endforeach
                ]
            },
            @endforeach
        ],
        chart: {
            height: 350,
            type: 'line',
        },
        stroke: {
            curve: 'straight'
            /*curve: 'smooth'*/
        },
        fill: {
            type:'solid',
            /*opacity: [0.35, 1],*/
        },
        labels: [
            @foreach($element->content[0] as $index => $dt)
                "{{$dt['x']}}" @if($index < count($element->content[0])) , @endif
            @endforeach
        ],
        markers: {
            size: 0
        },
        colors: [
            @foreach($colors as $color)
                '{{$color}}',
            @endforeach
        ],
        yaxis: [
            {
                title: {
                    text: '',
                },
            }/*,
            {
                opposite: true,
                title: {
                    text: 'Series B',
                },
            },*/
        ],
        tooltip: {
            shared: true,
            intersect: false,
            y: {
                formatter: function (y) {
                    if(typeof y !== "undefined") {
                        return  y.toFixed(0) + " points";
                    }
                    return y;
                }
            }
        }
    };

    var chart<?php echo $random;?> = new ApexCharts(
        document.querySelector("#chartArea_{{$random}}"),
        options
    );
    chart<?php echo $random;?>.render();



</script>
