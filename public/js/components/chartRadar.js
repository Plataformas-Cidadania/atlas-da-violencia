myChartRadar = undefined;

class ChartRadar extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            serie: this.props.serie,
            abrangencia: this.props.abrangencia,
            min: this.props.min,
            max: this.props.max,
            id: this.props.id,
            regions: this.props.regions,
            periodo: 0
        };
        //this.loadData = this.loadData.bind(this);
        this.loadChart = this.loadChart.bind(this);
    }

    componentDidMount() {
        this.loadData();
    }

    componentWillReceiveProps(props) {
        if (this.state.min != props.min || this.state.max != props.max || this.state.abrangencia != props.abrangencia) {
            /*this.setState({min: props.min, max: props.max}, function(){
                if(myChartRadar){
                    this.chartDestroy();
                }
                this.loadData();
            });*/
            this.setState({ min: props.min, max: props.max, abrangencia: props.abrangencia, regions: props.regions }, function () {
                if (myChartRadar) {
                    this.chartDestroy();
                }
                //this.loadChart(this.state.data);
                this.loadData();
            });
        }
    }

    /*loadData(){
        let _this = this;
        $.ajax("valores-regiao/"+this.state.min+"/"+this.state.max, {
            data: {},
            success: function(data){
                //console.log(data);
                _this.loadChartRadar(data);
            },
            error: function(data){
              console.log('erro');
            }
        })
    }*/

    loadData() {

        //console.log('MIN', this.state.min);
        //console.log('MAX', this.state.max);

        if (this.state.min && this.state.max) {

            this.setState({ loadingItems: true });

            //console.log(this.state.regions);
            $.ajax({
                method: 'GET',
                url: "valores-regiao/" + this.state.id + "/" + this.state.min + "/" + this.state.max + "/" + this.state.regions + "/" + this.state.abrangencia,
                cache: false,
                success: function (data) {
                    this.setState({ valoresRegioesPorPeriodo: data, periodo: data.max.periodo }, function () {
                        //console.log('CHART RADAR', this.state.valoresRegioesPorPeriodo);
                        this.loadChart(this.state.valoresRegioesPorPeriodo.max.valores);
                    });
                }.bind(this),
                error: function (xhr, status, err) {
                    console.log('erro');
                }.bind(this)
            });
        }
    }

    loadChart(data) {
        //console.log('CHART RADAR LOAD CHART', data);
        let labels = [];
        let values = [];
        for (let i in data) {
            labels[i] = data[i].sigla;
            values[i] = data[i].valor;
        }

        //console.log(values);

        let canvas2 = document.getElementById('myChartRadar');
        let dataChart = {
            labels: labels,
            datasets: [{
                label: this.state.serie,
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
            },
            animation: {
                onComplete: function () {
                    downloadCanvas("downChartBar", "myChartRadar", 'chartradar.png');
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
        myChartRadar.destroy();
    }

    render() {

        let periodo = null;
        if (this.state.periodo) {
            periodo = this.state.periodo.toString();
            if (this.props.periodicidade === "Anual") {
                periodo = periodo.substr(0, 4);
            }
            if (this.props.periodicidade === "Semestral" || this.props.periodicidade === "Trimestral" || this.props.periodicidade === "Mensal") {
                periodo = periodo.substr(0, 7);
            }
        }

        return React.createElement(
            "div",
            null,
            React.createElement(
                "div",
                { style: { textAlign: 'center', clear: 'both' } },
                React.createElement(
                    "button",
                    { className: "btn btn-primary btn-lg bg-pri", style: { border: '0' } },
                    periodo
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
                { id: "myChartRadar", width: "400", height: "200" },
                " "
            ),
            React.createElement(
                "div",
                { style: { float: 'right', marginLeft: '5px' } },
                React.createElement(
                    "a",
                    { id: "downChartBar", style: { cursor: 'pointer' } },
                    React.createElement("div", { className: "icons-components icons-component-download" })
                )
            ),
            React.createElement(
                "div",
                { style: { float: 'right', marginLeft: '5px' } },
                React.createElement("div", { className: "icons-components icons-component-print", onClick: () => printCanvas("myChartRadar") })
            ),
            React.createElement("div", { style: { clear: 'both' } })
        );
    }
}