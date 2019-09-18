<?php
    $random = rand(123456789,987654321);
?>

<style>
    #chartBar_{{$random}} {
        max-width: 650px;
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
                endingShape: 'rounded'
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
                text: '$ (thousands)'
            }
        },
        fill: {
            opacity: 1

        },
        tooltip: {
            y: {
                formatter: function (val) {
                    return "$ " + val + " thousands"
                }
            }
        }
    }

    var chart = new ApexCharts(
        document.querySelector("#chartBar_{{$random}}" ),
        options
    );

    chart.render();
</script>
