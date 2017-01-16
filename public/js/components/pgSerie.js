class PgSerie extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            min: 0,
            max: 0
        };
        this.changePeriodo = this.changePeriodo.bind(this);
    }

    changePeriodo(min, max) {
        this.setState({ min: min, max: max });
    }

    render() {
        return React.createElement(
            "div",
            null,
            React.createElement(RangePeriodo, { changePeriodo: this.changePeriodo }),
            React.createElement("br", null),
            React.createElement("div", { id: "mapid" }),
            React.createElement("br", null),
            React.createElement("br", null),
            React.createElement(
                "canvas",
                { id: "myChart", width: "400", height: "200" },
                " "
            ),
            React.createElement(
                "canvas",
                { id: "myChartRadar", width: "400", height: "200" },
                " "
            ),
            React.createElement(ListValoresSeries, { min: this.state.min, max: this.state.max })
        );
    }
}

ReactDOM.render(React.createElement(PgSerie, null), document.getElementById('pgSerie'));