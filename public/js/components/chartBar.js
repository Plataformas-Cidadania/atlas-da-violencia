myChartBar = [];
myChartBar[0] = undefined;
myChartBar[1] = undefined;

class ChartBar extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            serie: this.props.serie,
            data: {},
            smallLarge: this.props.smallLarge,
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
        if (this.state.smallLarge != props.smallLarge || this.state.intervalos != props.intervalos) {
            this.setState({ min: props.data.min.periodo, max: props.data.max.periodo, data: props.data, intervalos: props.intervalos, smallLarge: props.smallLarge }, function () {
                if (myChartBar[this.props.idBar]) {
                    this.chartDestroy();
                }
                this.loadChart(this.state.data);
            });
        }
    }

    /*loadData(){
        let _this = this;
        $.ajax("valores-regiao/"+this.state.min+"/"+this.state.max, {
            data: {},
            success: function(data){
                //console.log(data);
                _this.loadChartBar(data);
            },
            error: function(data){
                console.log('erro');
            }
        })
    }*/

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
            labels[i] = data.min.valores[i].sigla;
            valuesMin[i] = data.min.valores[i].valor;
        }

        let valuesMax = [];
        for (let i in data.min.valores) {
            valuesMax[i] = data.max.valores[i].valor;
        }

        //console.log(labels, values);
        //console.log(valuesMin, valuesMax);

        let canvas2 = document.getElementById('myChartBar' + this.props.idBar);
        //let colors = this.getColors(values);
        let colors = this.getColors();

        let datasets = [];

        datasets[0] = {
            label: this.state.min,
            backgroundColor: colors[0],
            borderColor: "rgba(179,181,198,1)",
            pointBackgroundColor: "rgba(179,181,198,1)",
            pointBorderColor: "#fff",
            pointHoverBackgroundColor: "#fff",
            pointHoverBorderColor: "rgba(179,181,198,1)",
            data: valuesMin
        };

        datasets[1] = {
            label: this.state.max,
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

        //console.log('chartbar - getcolors - intervalos', this.state.intervalos.length);
        /*if(this.state.intervalos.length > 0){
            let colors = [];
            for(let i in values){
                colors.push(convertHex(getColor(values[i], intervalos), 100));
            }
            return colors;
        }*/
    }

    render() {
        return React.createElement(
            "div",
            null,
            React.createElement(
                "div",
                { style: { textAlign: 'center', clear: 'both' } },
                React.createElement(
                    "button",
                    { className: "btn btn-primary btn-lg bg-pri", style: { border: '0' } },
                    this.state.min + ' / ' + this.state.max
                ),
                React.createElement(
                    "div",
                    { style: { marginTop: '-19px' } },
                    React.createElement("i", { className: "fa fa-sort-down fa-2x", style: { color: '#3498DB' } })
                )
            ),
            React.createElement("br", null),
            React.createElement(
                "canvas",
                { id: "myChartBar" + this.props.idBar, width: "400", height: "200" },
                " "
            )
        );
    }
}