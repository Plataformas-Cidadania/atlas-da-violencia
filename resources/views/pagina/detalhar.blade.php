@extends('layout')
@section('title', "Renda")
@section('content')
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script>


        google.load('visualization', '1', {
            'packages': ['geochart', 'table']
        });
        google.setOnLoadCallback(drawRegionsMap);

        function drawRegionsMap() {
            var data = google.visualization.arrayToDataTable([
                // Results For US States
                // State format must be "BR-**"
                // US represents region, while the ** section represents the individual state
                ['State', 'Views'],
                ['BR-SP', 300],
                ['BR-PE', 300],
                ['BR-AM', 400]

            ]);

            var view = new google.visualization.DataView(data)
            view.setColumns([0, 1])

            var options = {
                region: 'BR',
                resolution: 'provinces',
                width: 556,
                height: 347
            };

            var chart = new google.visualization.GeoChart(
                document.getElementById('chart_div'));
            chart.draw(data, options);

            var geochart = new google.visualization.GeoChart(
                document.getElementById('chart_div'));
            var options = {
                region: 'BR',
                resolution: 'provinces',
                width: 700,
                height: 500,
                colorAxis: {
                    colors: ['#acb2b9', '#2f3f4f']
                } // orange to blue
            };
            google.visualization.events.addListener(geochart, 'regionClick', function (eventData) {
                // maybe you want to change the data table here...
                options['region'] = eventData.region;
                options['resolution'] = 'provinces';
                options['displayMode'] = 'markers';

                var data = google.visualization.arrayToDataTable([
                    // Add Results for Individual State
                    // Format needs to match what is below so that it locates the correct position
                    // Additional information can be added to array
                    // Uses first value in 2nd column to determine scale for markers
                    // Use AJAX to load this on regionClick
                    ['City', 'Views'],
                    ['Recife, PE', 200],
                    ['Manaus, AM', 300],
                    ['Santos, SP', 400],
                    ['Campinas, SP', 400],

                ]);

                geochart.draw(data, options);
                var table = new google.visualization.Table(document.getElementById('table'));
                table.draw(data, null);

            });
            geochart.draw(data, options);

        };





    </script>


<div class="container">
    <div id="chart_div"></div>
</div>



    {{--http://pt.stackoverflow.com/questions/122354/como-exibir-o-estado-em-um-geochart--}}
@endsection

