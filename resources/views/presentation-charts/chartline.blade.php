<?php
    $random = rand(123456789,987654321);
?>

<style>
    #chartLine_{{$random}} {
        width: 100%;
        margin: 35px auto;
    }
</style>
<div id="chartLine_{{$random}}"></div>
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
                blur: 10,
                opacity: 1
            },
            toolbar: {
                show: false
            }
        },
        colors: ['#77B6EA', '#545454'],
        dataLabels: {
            enabled: true,
        },
        stroke: {
            curve: 'smooth'
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
            size: 6
        },
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
            },
            min: 5,
            max: 40
        },
        legend: {
            position: 'top',
            horizontalAlign: 'right',
            floating: true,
            offsetY: -25,
            offsetX: -5
        }
    }

    var chart<?php echo $random;?> = new ApexCharts(
        document.querySelector("#chartLine_{{$random}}" ),
        options
    );

    chart<?php echo $random;?>.render();
</script>