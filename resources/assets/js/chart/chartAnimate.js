

var datasets = [];

var labels = [];
var intervalo = null;
var ctx = null;

var config = {};
var values = [];


function homeChart(data, titulo){
    //var MONTHS = ["1996", "1997", "1998", "1999", "2000", "2001", "2002", "2003", "2004", "2005", "2006", "2007", "2008", "2009", "2010", "2011", "2012", "2013", "2014", "2015", "2016", "2017"];

    let count = 0;
    for(let i in data){
        labels[count] = i.substr(0, 4);
        values[count] = data[i];
        count++;
    }

    var colors = [
        '#008ECC',
        '#F29E19',
        '#008239',
        '#E96D38',
        '#E13746',
        '#D93984',
        '#005293',
        '#009839',
        '#502382',
        '#DDD203'
    ];

    /*for(let i in data){
        values[i] = [];

        for(let j in data[i]['valores']){
            if(!labels.includes(j.substr(0, 4))){
                labels.push(j.substr(0, 4))//ano
            }
            values[i].push(data[i]['valores'][j])

        }

        datasets.push({
            label: data[i]['titulo'],
            backgroundColor: colors[i],
            borderColor: colors[i],
            data: [values[i][0], values[i][1]],
            fill: false,
        });
    }

    config = {
        type: 'line',
        data: {
            labels: [labels[0], labels[1]],
            datasets: datasets
        },
        options: {
            responsive: true,
            title:{
                display:true,
            }
        }
    };*/


    config = {
        type: 'line',
        data: {
            labels: [labels[0], labels[1]],
            datasets: [{
                label: titulo,
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

/*function counterTime(){
    if(config.data.labels.length == labels.length){
        clearInterval(intervalo);
    }

    if (config.data.datasets.length > 0 && config.data.labels.length < labels.length) {
        var label = labels[config.data.labels.length % labels.length];
        config.data.labels.push(label);

        config.data.datasets.forEach(function(dataset, index) {
            var value = values[index][dataset.data.length % values.length];
            dataset.data.push(value);
        });

        window.myLine.update();
    }
}*/

function counterTime(){
    if(config.data.labels.length == labels.length){
        clearInterval(intervalo);
    }

    if (config.data.datasets.length > 0 && config.data.labels.length < labels.length) {
        var label = labels[config.data.labels.length % labels.length];
        config.data.labels.push(label);

        config.data.datasets.forEach(function(dataset) {
            var value = values[config.data.datasets[0].data.length % values.length];
            dataset.data.push(value);
        });

        window.myLine.update();
    }
}

window.onload = function() {

    /*$.ajax("home-chart/1", {
        data: {},
        success: function(data){
            console.log(data);
            homeChart(data);

        },
        error: function(data){
            console.log('erro');
        }
    });

    ctx = document.getElementById("canvas").getContext("2d");
    window.myLine = new Chart(ctx, config);
    intervalo = window.setInterval('counterTime()', 1000);*/


};