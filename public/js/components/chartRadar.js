myChartRadar = undefined;

class ChartRadar extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            min: 0,
            max: 0
        };
        this.loadData = this.loadData.bind(this);
    }

    componentWillReceiveProps(props) {
        if (this.state.min != props.min || this.state.max != props.max) {
            this.setState({ min: props.min, max: props.max }, function () {
                if (myChartBar) {
                    this.chartDestroy();
                }
                this.loadData();
            });
        }
    }

    loadData() {
        let _this = this;
        $.ajax("valores-regiao/" + this.state.min + "/" + this.state.max, {
            data: {},
            success: function (data) {
                //console.log(data);
                _this.loadChartRadar(data);
            },
            error: function (data) {
                console.log('erro');
            }
        });
    }

    loadChartRadar(data) {
        //console.log(data);
        let labels = [];
        let values = [];
        for (let i in data) {
            labels[i] = data[i].uf;
            values[i] = data[i].total;
        }

        //console.log(values);

        let canvas2 = document.getElementById('myChartRadar');
        let dataChart = {
            labels: labels,
            datasets: [{
                label: "Homicidios no Brasil",
                backgroundColor: "rgba(179,181,198,0.2)",
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

        myChartRadar = new Chart(canvas2, {
            type: 'radar',
            data: dataChart,
            options: options2
        });
    }

    chartDestroy() {
        //console.log(this.state.myRadarChart);
        //this.state.myRadarChart.destroy();
        myChartRadar.destroy();
        //destroyChartRadar();
    }

    render() {
        return React.createElement(
            "canvas",
            { id: "myChartRadar", width: "400", height: "200" },
            " "
        );
    }
}