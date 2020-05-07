<?php
    $random = rand(123456789,987654321);
    $setting = \App\Setting::first();
    $colors = explode(',', $setting->cores_serie_home);
?>

<style>
    #chartBar_{{$random}} {
        width: 100%;
        margin: 35px auto;
    }
</style>
<div id="chartBar_{{$random}}"></div>
<script>
    var options = {
        chart: {
            height: 350,
            type: 'bar',
        },
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: '55%',
                /*endingShape: 'rounded'*/
            },
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            show: true,
            width: 2,
            colors: ['transparent']
        },
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
        colors: [
            @foreach($colors as $color)
                '{{$color}}',
            @endforeach
        ],
        xaxis: {
            categories: [
                @foreach($element->content[0] as $index => $dt)
                     "{{$dt['x']}}" @if($index < count($element->content[0])) , @endif
                @endforeach
            ]
            /*categories: ['Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct'],*/
        },
        yaxis: {
            title: {
                text: ''
            }
        },
        fill: {
            opacity: 1

        },
        tooltip: {
            y: {
                formatter: function (val) {
                    return val;
                }
            }
        }
    }

    var chart<?php echo $random;?> = new ApexCharts(
        document.querySelector("#chartBar_{{$random}}" ),
        options
    );

    chart<?php echo $random;?>.render();
</script>
