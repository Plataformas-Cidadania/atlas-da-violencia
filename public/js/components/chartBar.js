myChartBar = undefined;

class ChartBar extends React.Component {
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
                _this.loadChartBar(data);
            },
            error: function (data) {
                console.log('erro');
            }
        });
    }

    loadChartBar(data) {
        //console.log(data);
        let labels = [];
        let values = [];
        for (let i in data) {
            labels[i] = data[i].uf;
            values[i] = data[i].total;
        }

        //console.log(values);

        let canvas2 = document.getElementById('myChartBar');
        let colors = this.getColors(values);
        let dataChart = {
            labels: labels,
            datasets: [{
                label: "Homicidios no Brasil",
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

        myChartBar = new Chart(canvas2, {
            type: 'bar',
            data: dataChart,
            options: options2
        });
    }

    chartDestroy() {
        console.log(this.state.myBarChart);
        //this.state.myBarChart.destroy();
        myChartBar.destroy();
        //destroyChartBar();
    }

    getColors(values) {
        let colors = [];
        for (let i in values) {
            colors.push(convertHex(getColor(values[i]), 100));
        }
        return colors;
    }

    render() {
        return React.createElement(
            "canvas",
            { id: "myChartBar", width: "400", height: "200" },
            " "
        );
    }
}