


var labels = [];
window.onload = function() {

    $.ajax("home-chart/"+this.state.id, {
        data: {},
        success: function(data){
            console.log(data);
            homeChart(data);

        },
        error: function(data){
            console.log('erro');
        }
    })

    var ctx = document.getElementById("canvas").getContext("2d");
    window.myLine = new Chart(ctx, config);
    var intervalo = window.setInterval('counterTime()', 1000);


};

var config = {};
var values = {};
function homeChart(data){
    //var MONTHS = ["1996", "1997", "1998", "1999", "2000", "2001", "2002", "2003", "2004", "2005", "2006", "2007", "2008", "2009", "2010", "2011", "2012", "2013", "2014", "2015", "2016", "2017"];

    for(let i in data){
        labels[i] = data[i].periodo;
        values[i] = data[i].valor;
    }

    config = {
        type: 'line',
        data: {
            labels: [labels[0], labels[1]],
            datasets: [{
                label: "My First dataset",
                backgroundColor: window.chartColors.blue,
                borderColor: window.chartColors.blue,
                data: [values[0], values[1]],
                fill: false,
            }]
        },
        options: {
            responsive: true,
            title:{
                display:true,
            }
        }
    };
}

function counterTime(){
    if(config.data.labels.length == labels.length){
        clearInterval(intervalo);
    }

    if (config.data.datasets.length > 0 && config.data.labels.length < labels.length) {
        var label = labels[config.data.labels.length % labels.length];
        config.data.labels.push(label);

        config.data.datasets.forEach(function(dataset) {
            var value = labels[config.data.values.length % values.length];
            dataset.data.push(value);
        });

        window.myLine.update();
    }
}