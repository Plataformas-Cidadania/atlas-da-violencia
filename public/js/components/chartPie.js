iPie = 0;

myChartPie = undefined; //irá receber o objeto chart e com isso poderá ser utilizado para destruilo.


class ChartPie extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            data: {},
            min: 0,
            max: 0,
            intervalos: this.props.intervalos
        };
        //this.loadData = this.loadData.bind(this);
        this.loadChart = this.loadChart.bind(this);
    }

    componentWillReceiveProps(props) {
        if (this.state.min != props.data.min || this.state.max != props.data.max) {
            /*this.setState({min: props.min, max: props.max}, function(){
                if(myChartPie){
                    this.chartDestroy();
                }
                this.loadData();
            });*/
            this.setState({ min: props.data.min, max: props.data.max, data: props.data.values, intervalos: props.intervalos }, function () {
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
            labels[i] = data[i].uf;
            values[i] = data[i].total;
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
        if (this.state.intervalos.length > 0) {
            let colors = [];
            for (let i in values) {
                colors.push(convertHex(getColor(values[i], intervalos), 100));
            }
            console.log('chartPie', colors);
            return colors;
        }
    }

    render() {
        console.log('chartPie', intervalos);
        return React.createElement(
            "canvas",
            { id: "myChartPie", width: "400", height: "200" },
            " "
        );
    }
}