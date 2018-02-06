class Page extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            type: null
        };

        this.checkType = this.checkType.bind(this);
    }

    checkType(type) {
        this.setState({ type: type });
    }

    render() {

        return React.createElement(
            "div",
            null,
            React.createElement(
                "div",
                { className: "container" },
                React.createElement(
                    "h1",
                    null,
                    "Acidentes de Transito"
                ),
                React.createElement("div", { className: "line_title bg-pri" }),
                React.createElement("br", null),
                React.createElement("br", null),
                React.createElement(Filters, null)
            ),
            React.createElement("br", null),
            React.createElement(Map, { id: "1", type: this.state.type })
        );
    }
}

ReactDOM.render(React.createElement(Page, null), document.getElementById('page'));