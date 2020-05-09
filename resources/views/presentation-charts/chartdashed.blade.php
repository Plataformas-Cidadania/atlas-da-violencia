<?php
    $random = rand(123456789,987654321);
    $setting = \App\Setting::first();
    $colors = explode(',', $setting->cores_serie_home);
?>

<style>
    #chartDashed_{{$random}} {
        width: 100%;
        margin: 35px auto;
    }
</style>
<div id="chartDashed_{{$random}}"></div>
<script>
    var options = {
        chart: {
            height: 350,
            type: 'line',
            shadow: {
                enabled: true,
                color: '#000',
                top: 18,
                left: 7,
                blur: 5,
                opacity: 1
            },
            toolbar: {
                show: false
            },
            zoom: {
                enabled: true
            }
        },
        title: {
            text: 'Título série'
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
        stroke: {
           /* curve: "straight"*/
             width: [
                 @foreach($element->content as $index => $dataset)
                 3,
                 @endforeach
             ],
             dashArray:[
                 @foreach($element->content as $index => $dataset)
                 [0, 2, 3],
                 @endforeach
            ]
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
        grid: {
            borderColor: '#e7e7e7',
            row: {
                colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
                opacity: 0.5
            },
        },
        markers: {
            size: 0
        },
        xaxis: {
            type: 'datetime',
            categories: [
                @foreach($element->content[0] as $index => $dt)
                     "{{$dt['x']}}" @if($index < count($element->content[0])) , @endif
                @endforeach
            ]
            /*categories: ['Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct'],*/
        },
        yaxis: {
            labels: {
                formatter: function (y) {
                    return y.toFixed(0) + "M";
                }
            }
        },
        legend: {
            /*position: 'top',
            horizontalAlign: 'right',
            floating: true,
            offsetY: -25,
            offsetX: -5*/
            position: 'top',
            offsetY: 0
        }
    }

    var chart<?php echo $random;?> = new ApexCharts(
        document.querySelector("#chartDashed_{{$random}}" ),
        options
    );

    chart<?php echo $random;?>.render();
</script>
