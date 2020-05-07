<?php
    $random = rand(123456789,987654321);
    $setting = \App\Setting::first();
    $colors = explode(',', $setting->cores_serie_home);
?>

<style>
    #chartBarStacked_{{$random}} {
        width: 100%;
        margin: 35px auto;
    }
</style>
<div id="chartBarStacked_{{$random}}"></div>
<script>

    var options = {
        /*series: [{
            name: 'PRODUCT A',
            data: [44, 55, 41, 67, 22, 43]
        }, {
            name: 'PRODUCT B',
            data: [13, 23, 20, 8, 13, 27]
        }, {
            name: 'PRODUCT C',
            data: [11, 17, 15, 15, 21, 14]
        }, {
            name: 'PRODUCT D',
            data: [21, 7, 25, 13, 22, 8]
        }],*/
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
            height: 350,
            stacked: true,
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
            position: 'right',
            offsetY: 40
        },
        fill: {
            opacity: 1
        }
    };

    //var chart = new ApexCharts(document.querySelector("#chartBarStacked_{{$random}}"), options);
    var chart<?php echo $random;?> = new ApexCharts(
        document.querySelector("#chartBarStacked_{{$random}}"),
        options
    );

    chart<?php echo $random;?>.render();


</script>
