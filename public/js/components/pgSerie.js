class PgSerie extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            id: this.props.id,
            serie: this.props.serie,
            loading: false,
            intervalos: [],
            totaisRegioesPorPeriodo: { min: 0, max: 0, values: {} },
            min: 0,
            max: 0,
            periodos: [],
            showMap: true,
            showCharts: true,
            showRegions: true,
            showTable: true,
            showCalcs: true,
            chartLine: true,
            chartRadar: false,
            chartBar: false,
            chartPie: false
        };
        this.loading = this.loading.bind(this);
        this.changePeriodo = this.changePeriodo.bind(this);
        this.setPeriodos = this.setPeriodos.bind(this);
        this.showHide = this.showHide.bind(this);
        this.loadData = this.loadData.bind(this);
        this.setIntervalos = this.setIntervalos.bind(this);
    }

    loading(status) {
        this.setState({ loading: status });
    }

    changePeriodo(min, max) {
        this.setState({ min: min, max: max }, function () {
            this.loadData();
        });
    }

    setPeriodos(periodos) {
        this.setState({ periodos: periodos });
    }

    loadData() {
        $.ajax({
            method: 'GET',
            url: "valores-regiao/" + this.state.id + "/" + this.props.tipoValores + "/" + this.state.min + "/" + this.state.max,
            cache: false,
            success: function (data) {
                console.log('pgSerie', data);
                let totais = {
                    min: this.state.min,
                    max: this.state.max,
                    values: data
                };
                this.setState({ totaisRegioesPorPeriodo: totais });

                ///////////////////////////////////////////////////////////
                ///////////////////////////////////////////////////////////
                let valores = [];
                for (let i in data) {
                    valores[i] = data[i].total;
                }
                console.log('pgSerie', valores);
                let valoresOrdenados = valores.sort(function (a, b) {
                    return a - b;
                });
                console.log('pgSerie', valoresOrdenados);

                intervalos = gerarIntervalos(valoresOrdenados);
                console.log('pgSerie', intervalos);
                ///////////////////////////////////////////////////////////
                ///////////////////////////////////////////////////////////


                //loadMap(data);
            }.bind(this),
            error: function (xhr, status, err) {
                console.log('erro');
            }.bind(this)
        });
    }

    changeChart(chart) {
        this.setState({ chartLine: false, chartRadar: false, chartBar: false, chartPie: false }, function () {
            let chartChosen = {};
            chartChosen[chart] = true;
            this.setState(chartChosen);
        });
    }

    showHide(target) {
        let value = this.state['show' + target];
        let inverseValue = !value;
        this.setState({ ['show' + target]: inverseValue });
    }

    setIntervalos(intervalos) {
        this.setState({ intervalos: intervalos });
    }

    render() {

        return React.createElement(
            "div",
            null,
            React.createElement(
                "div",
                { className: "text-center", style: { display: this.state.loading ? 'block' : 'none' } },
                React.createElement("br", null),
                React.createElement("br", null),
                React.createElement(
                    "i",
                    { className: "fa fa-5x fa-spinner fa-spin" },
                    " "
                )
            ),
            React.createElement(
                "div",
                { style: { visibility: this.state.loading ? 'disable' : 'enable' } },
                React.createElement(
                    "div",
                    { className: "row" },
                    React.createElement(
                        "div",
                        { className: "col-md-6 h3", style: { margin: 0 } },
                        React.createElement("img", { style: { marginLeft: '5px' }, src: "imagens/links/8516-01.png", width: "52", alt: "", title: "" }),
                        "\xA0",
                        this.state.serie
                    ),
                    React.createElement(
                        "div",
                        { className: "col-md-6 text-right" },
                        React.createElement("div", { className: "icons-groups icon-group-print", style: { display: 'none', marginLeft: '5px' }, title: "" }),
                        React.createElement("div", { className: "icons-groups" + (this.state.showCalcs ? " icon-group-calc" : " icon-group-calc-disable"),
                            style: { marginLeft: '5px' }, onClick: () => this.showHide('Calcs'), title: "" }),
                        React.createElement("div", { className: "icons-groups" + (this.state.showTable ? " icon-group-table" : " icon-group-table-disable"),
                            style: { marginLeft: '5px' }, onClick: () => this.showHide('Table'), title: "" }),
                        React.createElement("div", { className: "icons-groups" + (this.state.showRegions ? " icon-group-rate" : " icon-group-rate-disable"),
                            style: { marginLeft: '5px' }, onClick: () => this.showHide('Regions'), title: "" }),
                        React.createElement("div", { className: "icons-groups" + (this.state.showCharts ? " icon-group-chart" : " icon-group-chart-disable"),
                            style: { marginLeft: '5px' }, onClick: () => this.showHide('Charts'), title: "" }),
                        React.createElement("div", { className: "icons-groups" + (this.state.showMap ? " icon-group-map" : " icon-group-map-disable"),
                            style: { marginLeft: '5px' }, onClick: () => this.showHide('Map'), title: "" })
                    )
                ),
                React.createElement("br", null),
                React.createElement("div", { className: "line_title bg-pri" }),
                React.createElement("br", null),
                React.createElement(RangePeriodo, { id: this.state.id, changePeriodo: this.changePeriodo, setPeriodos: this.setPeriodos, loading: this.loading }),
                React.createElement("br", null),
                React.createElement("br", null),
                React.createElement("hr", null),
                React.createElement("br", null),
                React.createElement(
                    "div",
                    { style: { display: this.state.showMap ? 'block' : 'none' } },
                    React.createElement(Map, { id: this.state.id, tipoValores: this.props.tipoValores, min: this.state.min, max: this.state.max, setIntervalos: this.setIntervalos }),
                    React.createElement("br", null),
                    React.createElement("hr", null),
                    React.createElement("br", null)
                ),
                React.createElement(
                    "div",
                    { style: { display: this.state.showCharts ? 'block' : 'none' } },
                    React.createElement(
                        "div",
                        null,
                        React.createElement(
                            "div",
                            { style: { textAlign: 'right' } },
                            React.createElement("div", { className: "icons-charts" + (this.state.chartLine ? " icon-chart-line" : " icon-chart-line-disable"),
                                style: { marginLeft: '5px' }, onClick: () => this.changeChart('chartLine'), title: "" }),
                            React.createElement("div", { className: "icons-charts" + (this.state.chartBar ? " icon-chart-bar" : " icon-chart-bar-disable"),
                                style: { marginLeft: '5px' }, onClick: () => this.changeChart('chartBar'), title: "" }),
                            React.createElement("div", { className: "icons-charts" + (this.state.chartRadar ? " icon-chart-radar" : " icon-chart-radar-disable"),
                                style: { marginLeft: '5px' }, onClick: () => this.changeChart('chartRadar'), title: "" }),
                            React.createElement("div", { className: "icons-charts" + (this.state.chartPie ? " icon-chart-pie" : " icon-chart-pie-disable"),
                                style: { marginLeft: '5px' }, onClick: () => this.changeChart('chartPie'), title: "" })
                        ),
                        React.createElement(
                            "div",
                            { style: { display: this.state.chartLine ? 'block' : 'none' } },
                            React.createElement(ChartLine, { id: this.state.id, serie: this.state.serie, min: this.state.min, max: this.state.max, periodos: this.state.periodos })
                        ),
                        React.createElement(
                            "div",
                            { style: { display: this.state.chartBar ? 'block' : 'none' } },
                            React.createElement(ChartBar, { serie: this.state.serie, intervalos: this.state.intervalos, data: this.state.totaisRegioesPorPeriodo })
                        ),
                        React.createElement(
                            "div",
                            { style: { display: this.state.chartRadar ? 'block' : 'none' } },
                            React.createElement(ChartRadar, { serie: this.state.serie, data: this.state.totaisRegioesPorPeriodo })
                        ),
                        React.createElement(
                            "div",
                            { style: { display: this.state.chartPie ? 'block' : 'none' } },
                            React.createElement(ChartPie, { intervalos: this.state.intervalos, data: this.state.totaisRegioesPorPeriodo })
                        )
                    ),
                    React.createElement("br", null),
                    React.createElement("hr", null),
                    React.createElement("br", null)
                ),
                React.createElement(
                    "div",
                    { style: { display: this.state.showRegions ? 'block' : 'none' } },
                    React.createElement(Regions, { id: this.state.id, data: this.state.totaisRegioesPorPeriodo }),
                    React.createElement("br", null),
                    React.createElement("hr", null),
                    React.createElement("br", null)
                ),
                React.createElement(
                    "div",
                    { style: { display: this.state.showTable ? 'block' : 'none' } },
                    React.createElement(ListValoresSeries, { min: this.state.min, max: this.state.max, data: this.state.totaisRegioesPorPeriodo }),
                    React.createElement("br", null),
                    React.createElement("hr", null),
                    React.createElement("br", null)
                ),
                React.createElement(
                    "div",
                    { style: { display: this.state.showCalcs ? 'block' : 'none' } },
                    React.createElement(Calcs, { id: this.state.id, serie: this.state.serie, data: this.state.totaisRegioesPorPeriodo })
                )
            )
        );
    }
}

ReactDOM.render(React.createElement(PgSerie, { id: serie_id, serie: serie, tipoValores: tipoValores }), document.getElementById('pgSerie'));