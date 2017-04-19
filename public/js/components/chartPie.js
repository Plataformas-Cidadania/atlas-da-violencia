iPie = 0;

myChartPie = undefined; //irá receber o objeto chart e com isso poderá ser utilizado para destruilo.


class ChartPie extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            data: {},
            periodo: 0,
            intervalos: this.props.intervalos
        };
        //this.loadData = this.loadData.bind(this);
        this.loadChart = this.loadChart.bind(this);
    }

    componentWillReceiveProps(props) {
        if (this.state.periodo != props.data.periodo || this.state.intervalos != props.intervalos) {
            /*this.setState({min: props.min, max: props.max}, function(){
                if(myChartPie){
                    this.chartDestroy();
                }
                this.loadData();
            });*/
            this.setState({ periodo: props.data.periodo, data: props.data.valores, intervalos: props.intervalos }, function () {
                if (myChartPie) {
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
                _this.loadChartPie(data);
            },
            error: function(data){
              console.log('erro');
            }
        })
    }*/

    loadChart(data) {

        let labels = [];
        let values = [];
        for (let i in data) {
            labels[i] = data[i].sigla;
            values[i] = data[i].valor;
        }

        //console.log(values);

        let canvas2 = document.getElementById('myChartPie');
        let colors = this.getColors(values);
        let dataChart = {
            labels: labels,
            datasets: [{
                label: "Homicidios no Brasil",
                backgroundColor: colors,
                /*borderColor: "rgba(179,181,198,1)",*/
                pointBackgroundColor: "rgba(179,181,198,1)",
                pointBorderColor: "#000",
                pointHoverBackgroundColor: "#f00",
                pointHoverBorderColor: "rgba(179,181,198,1)",
                data: values
            }]
        };

        let options2 = {
            legend: {
                display: true,
                position: 'bottom'
            }
        };

        myChartPie = new Chart(canvas2, {
            type: 'pie',
            data: dataChart,
            options: options2
        });
    }

    chartDestroy() {
        myChartPie.destroy();
    }

    getColors(values) {

        let colors = [];
        for (let i in colors2) {
            colors.push(convertHex(colors2[i], 100));
        }
        return colors;

        /*if(this.state.intervalos.length > 0){
            let colors = [];
            for(let i in values){
                colors.push(convertHex(getColor(values[i], intervalos), 100));
            }
            //console.log('chartPie', colors);
            return colors;
        }*/
    }

    render() {
        //console.log('chartPie', intervalos);
        return React.createElement(
            "div",
            null,
            React.createElement(
                "div",
                { style: { textAlign: 'center', clear: 'both' } },
                React.createElement(
                    "button",
                    { className: "btn btn-primary btn-lg bg-pri", style: { border: '0' } },
                    this.state.max
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
                { id: "myChartPie", width: "400", height: "200" },
                " "
            )
        );
    }
}