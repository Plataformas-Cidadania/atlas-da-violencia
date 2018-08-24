myChartBar = [];
myChartBar[0] = undefined;
myChartBar[1] = undefined;

class ChartBar extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            id: this.props.id,
            serie: this.props.serie,
            /*data: {},*/
            valoresRegioesPorPeriodo: { min: 0, max: 0, values: {} },
            /*smallLarge: this.props.smallLarge,*/
            smallLarge: [0, 1],
            periodo: 0,
            min: 0,
            max: 0,
            intervalos: this.props.intervalos
        };
        //this.loadData = this.loadData.bind(this);
        this.loadChart = this.loadChart.bind(this);
    }

    componentWillReceiveProps(props) {
        //console.log(props.smallLarge);
        //console.log(props.data);
        /*if(this.state.smallLarge != props.smallLarge || this.state.intervalos != props.intervalos){
            this.setState({min: props.data.min.periodo, max:props.data.max.periodo, data: props.data, intervalos: props.intervalos, smallLarge: props.smallLarge}, function(){
                if(myChartBar[this.props.idBar]){
                    this.chartDestroy();
                }
                this.loadChart(this.state.data);
            });
        }*/

        if (this.state.min != props.min || this.state.max != props.max, this.state.abrangencia != props.abrangencia) {
            this.setState({ min: props.min, max: props.max, abrangencia: props.abrangencia }, function () {
                if (myChartBar[this.props.idBar]) {
                    this.chartDestroy();
                }
                this.loadData();
            });
        }
    }

    calcSmallLarge(minValores, maxValores) {
        let valores = [];
        minValores.find(function (item) {
            valores.push(parseFloat(item.valor));
        });
        maxValores.find(function (item) {
            valores.push(parseFloat(item.valor));
        });
        let valoresSort = valores.sort(function (a, b) {
            return a - b;
        });
        let smallLarge = [];
        smallLarge[0] = valoresSort[0] - 10;
        if (valoresSort[0] > 0 && valoresSort[0] < 10) {
            smallLarge[0] = valoresSort[0];
        }

        smallLarge[1] = valoresSort[valoresSort.length - 1];
        return smallLarge;
    }

    loadData() {
        if (this.state.min && this.state.max) {
            //console.log(this.props.regions);
            $.ajax({
                method: 'GET',
                //url: "valores-regiao/"+this.state.id+"/"+this.props.tipoValores+"/"+this.state.min+"/"+this.state.max,
                url: "valores-regiao/" + this.state.id + "/" + this.state.min + "/" + this.state.max + "/" + this.props.regions + "/" + this.props.abrangencia,
                //url: "valores-regiao/"+this.state.id+"/"+this.state.max,
                cache: false,
                success: function (data) {
                    //console.log('pgSerie', data);
                    //os valores menor e maior para serem utilizados no chartBar
                    let smallLarge = this.calcSmallLarge(data.min.valores, data.max.valores);
                    /*let totais = {
                     min: this.state.min,
                     max: this.state.max,
                     values: data
                     };*/
                    this.setState({ valoresRegioesPorPeriodo: data, smallLarge: smallLarge }, function () {
                        if (myChartBar[this.props.idBar]) {
                            this.chartDestroy();
                        }
                        this.loadChart(this.state.valoresRegioesPorPeriodo);
                    });

                    //loadMap(data);
                }.bind(this),
                error: function (xhr, status, err) {
                    console.log('erro');
                }.bind(this)
            });
        }
    }

    loadChart(data) {
        //console.log(data);
        let labels = [];
        let values = [];
        //console.log(data);
        /*for(let i in data){
            labels[i] = data[i].sigla;
            values[i] = data[i].valor;
        }*/

        let valuesMin = [];
        for (let i in data.min.valores) {
            //labels[i] = data.min.valores[i].sigla;
            valuesMin[i] = data.min.valores[i].valor;
        }

        let valuesMax = [];
        for (let i in data.min.valores) {
            labels[i] = data.max.valores[i].sigla;
            valuesMax[i] = data.max.valores[i].valor;
        }

        //console.log(labels, values);
        //console.log(valuesMin, valuesMax);

        let canvas2 = document.getElementById('myChartBar' + this.props.idBar);
        //let colors = this.getColors(values);
        let colors = this.getColors();

        let datasets = [];

        datasets[0] = {
            label: formatPeriodicidade(this.state.min, this.props.periodicidade),
            backgroundColor: colors[0],
            borderColor: "rgba(179,181,198,1)",
            pointBackgroundColor: "rgba(179,181,198,1)",
            pointBorderColor: "#fff",
            pointHoverBackgroundColor: "#fff",
            pointHoverBorderColor: "rgba(179,181,198,1)",
            data: valuesMin
        };

        datasets[1] = {
            label: formatPeriodicidade(this.state.max, this.props.periodicidade),
            backgroundColor: colors[1],
            borderColor: "rgba(179,181,198,1)",
            pointBackgroundColor: "rgba(179,181,198,1)",
            pointBorderColor: "#fff",
            pointHoverBackgroundColor: "#fff",
            pointHoverBorderColor: "rgba(179,181,198,1)",
            data: valuesMax
        };

        let dataChart = {
            labels: labels,
            //labels: [labelsMin, labelsMax],
            datasets: datasets
            /*datasets: [
                {
                    label: this.state.serie,
                    backgroundColor: colors,
                    borderColor: "rgba(179,181,198,1)",
                    pointBackgroundColor: "rgba(179,181,198,1)",
                    pointBorderColor: "#fff",
                    pointHoverBackgroundColor: "#fff",
                    pointHoverBorderColor: "rgba(179,181,198,1)",
                    data: values
                }
            ]*/
        };

        //let max = ((this.state.smallLarge[1] - this.state.smallLarge[0]) / 6) + this.state.smallLarge[1];


        let options2 = {
            scale: {
                reverse: false,
                ticks: {
                    //beginAtZero: true,
                }
            },
            scales: {
                yAxes: [{
                    ticks: {
                        //max: this.state.smallLarge[1],
                        //min: this.state.smallLarge[0],
                        //stepSize: (this.state.smallLarge[1] - this.state.smallLarge[0]) / 5,
                        //stepSize: 2000,
                        //suggestedMax: 6,
                        //maxTicksLimit: 6


                    }
                }]
            },
            animation: {
                onComplete: function () {
                    downloadCanvas("downChartBar" + this.props.idBar, "myChartBar" + this.props.idBar, 'chartbar.png');
                }.bind(this)
            }
        };

        myChartBar[this.props.idBar] = new Chart(canvas2, {
            type: 'bar',
            data: dataChart,
            options: options2
        });
    }

    chartDestroy() {
        myChartBar[this.props.idBar].destroy();
        //myChartBar[this.props.idBar].update();
    }

    //getColors(values){
    getColors() {

        let colors = [];
        for (let i in colors2) {
            colors.push(convertHex(colors2[i], 100));
        }
        return colors;
    }

    render() {

        let min = formatPeriodicidade(this.state.min, this.props.periodicidade);
        let max = formatPeriodicidade(this.state.max, this.props.periodicidade);

        return React.createElement(
            "div",
            null,
            React.createElement(
                "div",
                { style: { textAlign: 'center', clear: 'both' } },
                React.createElement(
                    "button",
                    { className: "btn btn-primary btn-lg bg-pri", style: { border: '0' } },
                    min + ' / ' + max
                ),
                React.createElement(
                    "div",
                    { style: { marginTop: '-19px' } },
                    React.createElement("i", { className: "fa fa-sort-down fa-2x ft-pri" })
                )
            ),
            React.createElement("br", null),
            React.createElement(
                "canvas",
                { id: "myChartBar" + this.props.idBar, width: "400", height: "200" },
                " "
            ),
            React.createElement(
                "div",
                { style: { float: 'right', marginLeft: '5px' } },
                React.createElement(
                    "a",
                    { id: "downChartBar" + this.props.idBar, style: { cursor: 'pointer' } },
                    React.createElement("div", { className: "icons-components icons-component-download" })
                )
            ),
            React.createElement(
                "div",
                { style: { float: 'right', marginLeft: '5px' } },
                React.createElement("div", { className: "icons-components icons-component-print", onClick: () => printCanvas("myChartBar" + this.props.idBar) })
            ),
            React.createElement("div", { style: { clear: 'both' } })
        );
    }
}
