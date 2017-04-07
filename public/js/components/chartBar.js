myChartBar = [];
myChartBar[0] = undefined;
myChartBar[1] = undefined;

class ChartBar extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            serie: this.props.serie,
            data: {},
            periodo: 0,
            intervalos: this.props.intervalos
        };
        //this.loadData = this.loadData.bind(this);
        this.loadChart = this.loadChart.bind(this);
    }

    componentWillReceiveProps(props) {
        if (this.state.periodo != props.data.periodo || this.state.intervalos != props.intervalos) {
            this.setState({ periodo: props.data.periodo, data: props.data.valores, intervalos: props.intervalos }, function () {
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
        for (let i in data) {
            labels[i] = data[i].sigla;
            values[i] = data[i].valor;
        }

        //console.log(labels, values);

        let canvas2 = document.getElementById('myChartBar' + this.props.idBar);
        let colors = this.getColors(values);
        let dataChart = {
            labels: labels,
            datasets: [{
                label: this.state.serie,
                backgroundColor: colors,
                borderColor: "rgba(179,181,198,1)",
                pointBackgroundColor: "rgba(179,181,198,1)",
                pointBorderColor: "#fff",
                pointHoverBackgroundColor: "#fff",
                pointHoverBorderColor: "rgba(179,181,198,1)",
                data: values
            }]
        };

        let options2 = {
            scale: {
                reverse: false,
                ticks: {
                    beginAtZero: true
                }
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

    getColors(values) {
        //console.log('chartbar - getcolors - intervalos', this.state.intervalos.length);
        if (this.state.intervalos.length > 0) {
            let colors = [];
            for (let i in values) {
                colors.push(convertHex(getColor(values[i], intervalos), 100));
            }
            return colors;
        }
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
                    this.state.periodo
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