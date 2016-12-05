<link rel="stylesheet" href="css/all.css">
<div style="margin:auto; width: 1200px;">
    <div id="visualization"></div>
    <div style="width: 200px; margin:auto;"><button class="btn btn-primary" id="example1-b1">Change Value</button></div>
    <div style="width: 200px; margin:auto; text-align:center" id="value"></div>

</div>

<script src="js/all.js"></script>
<!--<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>-->
<!--<script src="https://google-developers.appspot.com/_static/c54aa247ac/js/prettify-bundle.js?hl=pt-br"></script>-->


<!--<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>-->
<!--<script src="https://google-developers.appspot.com/_static/c54aa247ac/js/jquery_ui-bundle.js?hl=pt-br"></script>-->
<script src="//www.google.com/jsapi?key=AIzaSyCZfHRnq7tigC-COeQRmoa9Cxr0vbrK6xw&hl=pt-br"></script>

<!--<script src="https://google-developers.appspot.com/_static/c54aa247ac/js/framebox.js?hl=pt-br"></script>-->
<script>
    function init() {
        var options = {

            height: 500,
            animation:{
                duration: 1000,
                easing: 'out',
            },
            vAxis: {minValue:0, maxValue:1000}
        };
        /*var data = new google.visualization.DataTable();
        data.addColumn('string', 'N');
        data.addColumn('number', 'Value');
        data.addRow(['V', 200]);*/

        var data = new google.visualization.DataTable();
        data.addColumn('string', 'N');
        data.addColumn('number', 'Value');
        //data.addColumn('{ role: "style" }', 'Value');
        data.addRow(['Extremamente pobre', 227]);
        data.addRow(['pobre', 648]);
        data.addRow(['Vulneravel', 1030]);
        data.addRow(['Baixa Classe Média', 1540]);
        data.addRow(['Média Classe Média', 1925]);
        data.addRow(['Alta Classe Média', 2813]);
        data.addRow(['Baixa Classe Alta', 4845]);
        data.addRow(['Alta Classe Alta', 12988]);

        /*var data = google.visualization.arrayToDataTable([
            ["Classe Element", "Density", { role: "style" } ],
            ["Extremamente pobre", 0.227, "#CCCCCC"],
            ["pobre", 0.648, "#CCCCCC"],
            ["Vulneravel C", 1.030, "#CCCCCC"],
            ["Baixa Classe Média", 1.540, "#CCCCCC"],
            ["Média Classe Média", 1.925, "#89C71C"],
            ["Alta Classe Média", 2.813, "#CCCCCC"],
            ["Baixa Classe Alta", 4.845, "color: #CCCCCC"],
            ["Alta Classe Alta", 12.988, "color: #CCCCCC"]
        ]);*/

        /*["Classe Element", "Density", { role: "style" } ],
            ["Extremamente pobre", 227, "#CCCCCC"],
            ["pobre", 648, "#CCCCCC"],
            ["Vulneravel C", 1030, "#CCCCCC"],
            ["Baixa Classe Média", 1540, "#CCCCCC"],
            ["Média Classe Média1", 1925, "#89C71C"],
            ["Alta Classe Média", 2813, "#CCCCCC"],
            ["Baixa Classe Alta", 4845, "color: #CCCCCC"],
            ["Alta Classe Alta", 12988, "color: #CCCCCC"]*/

        var chart = new google.visualization.ColumnChart(
            document.getElementById('visualization'));
        var button = document.getElementById('b1');

        function drawChart() {
            // Disabling the button while the chart is drawing.
            button.disabled = true;
            google.visualization.events.addListener(chart, 'ready',
                function() {
                    button.disabled = false;
                });
            chart.draw(data, options);
        }

        button = document.getElementById('example1-b1');

        button.onclick = function() {
            var newValue = 10000 - data.getValue(0, 1);
            var newValue2 = 10000 - data.getValue(1, 1);
            data.setValue(0, 1, newValue);
            data.setValue(1, 1, newValue2);
            document.getElementById('value').innerHTML = newValue;
            drawChart();
        }
        drawChart();
        document.getElementById('value').innerHTML = data.getValue(0, 1);
    }


    google.load('visualization', '1.1', {packages: ['corechart']});
    google.setOnLoadCallback(init);
</script>
