class PgSerie extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            min: 0,
            max: 0,
            periodos: [],
            chartLine: true,
            chartRadar: false,
            chartBar: false
        };
        this.changePeriodo = this.changePeriodo.bind(this);
        this.setPeriodos = this.setPeriodos.bind(this);
    }

    changePeriodo(min, max) {
        this.setState({ min: min, max: max });
    }

    setPeriodos(periodos) {
        this.setState({ periodos: periodos });
    }

    changeChart(chart) {
        this.setState({ chartLine: false, chartRadar: false, chartBar: false }, function () {
            let chartLine = {};
            chartLine[chart] = true;
            this.setState(chartLine);
        });
    }

    render() {
        return React.createElement(
            "div",
            null,
            React.createElement(RangePeriodo, { changePeriodo: this.changePeriodo, setPeriodos: this.setPeriodos }),
            React.createElement("br", null),
            React.createElement("div", { id: "mapid" }),
            React.createElement("br", null),
            React.createElement("br", null),
            React.createElement(
                "div",
                { style: { textAlign: 'right' } },
                React.createElement(
                    "i",
                    { className: "fa fa-3x fa-line-chart", onClick: () => this.changeChart('chartLine'),
                        style: { color: this.state.chartLine ? 'green' : '', cursor: 'pointer' } },
                    " "
                ),
                "\xA0\xA0\xA0\xA0\xA0",
                React.createElement(
                    "i",
                    { className: "fa fa-3x fa-bar-chart", onClick: () => this.changeChart('chartBar'),
                        style: { color: this.state.chartBar ? 'green' : '', cursor: 'pointer' } },
                    " "
                ),
                "\xA0\xA0\xA0\xA0\xA0",
                React.createElement(
                    "i",
                    { className: "fa fa-3x fa-star", onClick: () => this.changeChart('chartRadar'),
                        style: { color: this.state.chartRadar ? 'green' : '', cursor: 'pointer' } },
                    " "
                ),
                "\xA0"
            ),
            React.createElement(
                "div",
                { style: { display: this.state.chartLine ? 'block' : 'none' } },
                React.createElement(ChartLine, { min: this.state.min, max: this.state.max, periodos: this.state.periodos })
            ),
            React.createElement(
                "div",
                { style: { display: this.state.chartBar ? 'block' : 'none' } },
                React.createElement(ChartBar, { min: this.state.min, max: this.state.max })
            ),
            React.createElement(
                "div",
                { style: { display: this.state.chartRadar ? 'block' : 'none' } },
                React.createElement(ChartRadar, { min: this.state.min, max: this.state.max })
            ),
            React.createElement("br", null),
            React.createElement("br", null),
            React.createElement(ListValoresSeries, { min: this.state.min, max: this.state.max })
        );
    }
}

ReactDOM.render(React.createElement(PgSerie, null), document.getElementById('pgSerie'));