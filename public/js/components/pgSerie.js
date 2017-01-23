class PgSerie extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            totaisRegioesPorPeriodo: { min: 0, max: 0, values: {} },
            min: 0,
            max: 0,
            periodos: [],
            showMap: true,
            showCharts: true,
            showRates: true,
            showTable: true,
            showCalcs: true,
            chartLine: true,
            chartRadar: false,
            chartBar: false,
            chartPie: false
        };
        this.changePeriodo = this.changePeriodo.bind(this);
        this.setPeriodos = this.setPeriodos.bind(this);
        this.showHide = this.showHide.bind(this);
        this.loadData = this.loadData.bind(this);
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
            url: "valores-regiao/" + this.state.min + "/" + this.state.max,
            cache: false,
            success: function (data) {
                let totais = {
                    min: this.state.min,
                    max: this.state.max,
                    values: data
                };
                this.setState({ totaisRegioesPorPeriodo: totais });
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

    render() {
        return React.createElement(
            "div",
            null,
            React.createElement(
                "div",
                { className: "row" },
                React.createElement(
                    "div",
                    { className: "col-md-6 h3", style: { margin: 0 } },
                    React.createElement("img", { style: { marginLeft: '5px' }, src: "imagens/links/8516-01.png", width: "52", alt: "", title: "" }),
                    "\xA0Homic\xEDdios no Brasil"
                ),
                React.createElement(
                    "div",
                    { className: "col-md-6 text-right" },
                    React.createElement("div", { className: "icons-groups icon-group-print", style: { marginLeft: '5px' }, title: "" }),
                    React.createElement("div", { className: "icons-groups" + (this.state.showCalcs ? " icon-group-calc" : " icon-group-calc-disable"),
                        style: { marginLeft: '5px' }, onClick: () => this.showHide('Calcs'), title: "" }),
                    React.createElement("div", { className: "icons-groups" + (this.state.showTable ? " icon-group-table" : " icon-group-table-disable"),
                        style: { marginLeft: '5px' }, onClick: () => this.showHide('Table'), title: "" }),
                    React.createElement("div", { className: "icons-groups" + (this.state.showRates ? " icon-group-rate" : " icon-group-rate-disable"),
                        style: { marginLeft: '5px' }, onClick: () => this.showHide('Rates'), title: "" }),
                    React.createElement("div", { className: "icons-groups" + (this.state.showCharts ? " icon-group-chart" : " icon-group-chart-disable"),
                        style: { marginLeft: '5px' }, onClick: () => this.showHide('Charts'), title: "" }),
                    React.createElement("div", { className: "icons-groups" + (this.state.showMap ? " icon-group-map" : " icon-group-map-disable"),
                        style: { marginLeft: '5px' }, onClick: () => this.showHide('Map'), title: "" })
                )
            ),
            React.createElement("br", null),
            React.createElement("div", { className: "line_title bg-pri" }),
            React.createElement("br", null),
            React.createElement(RangePeriodo, { changePeriodo: this.changePeriodo, setPeriodos: this.setPeriodos }),
            React.createElement("br", null),
            React.createElement("br", null),
            React.createElement("hr", null),
            React.createElement("br", null),
            React.createElement(
                "div",
                { style: { display: this.state.showMap ? 'block' : 'none' } },
                React.createElement(Map, { min: this.state.min, max: this.state.max }),
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
                        React.createElement(ChartLine, { min: this.state.min, max: this.state.max, periodos: this.state.periodos })
                    ),
                    React.createElement(
                        "div",
                        { style: { display: this.state.chartBar ? 'block' : 'none' } },
                        React.createElement(ChartBar, { data: this.state.totaisRegioesPorPeriodo })
                    ),
                    React.createElement(
                        "div",
                        { style: { display: this.state.chartRadar ? 'block' : 'none' } },
                        React.createElement(ChartRadar, { data: this.state.totaisRegioesPorPeriodo })
                    ),
                    React.createElement(
                        "div",
                        { style: { display: this.state.chartPie ? 'block' : 'none' } },
                        React.createElement(ChartPie, { data: this.state.totaisRegioesPorPeriodo })
                    )
                ),
                React.createElement("br", null),
                React.createElement("hr", null),
                React.createElement("br", null)
            ),
            React.createElement(
                "div",
                { style: { display: this.state.showRates ? 'block' : 'none' } },
                React.createElement(Rates, null),
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
                "C\xE1lculos"
            )
        );
    }
}

ReactDOM.render(React.createElement(PgSerie, null), document.getElementById('pgSerie'));