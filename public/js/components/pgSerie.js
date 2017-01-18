class PgSerie extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            min: 0,
            max: 0,
            periodos: []
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

    render() {
        return React.createElement(
            "div",
            null,
            React.createElement(RangePeriodo, { changePeriodo: this.changePeriodo, setPeriodos: this.setPeriodos }),
            React.createElement("br", null),
            React.createElement("div", { id: "mapid" }),
            React.createElement("br", null),
            React.createElement("br", null),
            React.createElement(ChartLine, { min: this.state.min, max: this.state.max, periodos: this.state.periodos }),
            React.createElement(ChartRadar, { min: this.state.min, max: this.state.max }),
            React.createElement(ListValoresSeries, { min: this.state.min, max: this.state.max })
        );
    }
}

ReactDOM.render(React.createElement(PgSerie, null), document.getElementById('pgSerie'));