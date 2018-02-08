class Filters extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            types: [],
            typesAccident: []
        };

        this.checkType = this.checkType.bind(this);
        this.checkTypeAccident = this.checkTypeAccident.bind(this);
        this.actionFilter = this.actionFilter.bind(this);
    }

    checkType(types) {
        this.setState({ types: types }, function () {
            this.props.checkType(this.state.types);
        });
    }

    checkTypeAccident(types) {
        this.setState({ typesAccident: types }, function () {
            this.props.checkTypeAccident(this.state.typesAccident);
        });
    }

    actionFilter() {
        this.props.actionFilter();
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
                    { className: "col-md-6" },
                    React.createElement(RangeYear, null)
                ),
                React.createElement(
                    "div",
                    { className: "col-md-6" },
                    React.createElement(RangeMonth, null)
                )
            ),
            React.createElement("br", null),
            React.createElement(
                "div",
                { className: "row" },
                React.createElement(
                    "div",
                    { className: "col-md-3" },
                    React.createElement(
                        "fieldset",
                        null,
                        React.createElement(
                            "legend",
                            null,
                            "Locomo\xE7\xE3o"
                        ),
                        React.createElement(
                            "div",
                            { style: { margin: '10px' } },
                            React.createElement(Type, { checkType: this.checkType })
                        )
                    )
                ),
                React.createElement(
                    "div",
                    { className: "col-md-3" },
                    React.createElement(
                        "fieldset",
                        null,
                        React.createElement(
                            "legend",
                            null,
                            "Tipo de Acidente"
                        ),
                        React.createElement(
                            "div",
                            { style: { margin: '10px' } },
                            React.createElement(TypeAccident, { checkTypeAccident: this.checkTypeAccident })
                        )
                    )
                ),
                React.createElement(
                    "div",
                    { className: "col-md-3" },
                    React.createElement(
                        "fieldset",
                        null,
                        React.createElement(
                            "legend",
                            null,
                            "Regi\xE3o"
                        ),
                        React.createElement(
                            "div",
                            { style: { margin: '10px' } },
                            React.createElement(
                                "span",
                                null,
                                "Digite abaixo para filtrar"
                            ),
                            React.createElement("hr", { style: { margin: '10px 0' } }),
                            React.createElement("input", { type: "text", className: "form-control" })
                        )
                    )
                ),
                React.createElement(
                    "div",
                    { className: "col-md-3" },
                    React.createElement(
                        "fieldset",
                        null,
                        React.createElement(
                            "legend",
                            null,
                            "Sexo"
                        ),
                        React.createElement(
                            "div",
                            { style: { margin: '10px' } },
                            React.createElement(
                                "span",
                                null,
                                "Selecione para filtrar"
                            ),
                            React.createElement("hr", { style: { margin: '10px 0' } }),
                            React.createElement(
                                "select",
                                { name: "sexo", id: "", className: "form-control" },
                                React.createElement(
                                    "option",
                                    { value: "" },
                                    "Todos"
                                ),
                                React.createElement(
                                    "option",
                                    { value: "" },
                                    "Masculino"
                                ),
                                React.createElement(
                                    "option",
                                    { value: "" },
                                    "Feminino"
                                )
                            )
                        )
                    )
                )
            ),
            React.createElement("br", null),
            React.createElement(
                "div",
                { className: "row" },
                React.createElement(
                    "div",
                    { className: "col-md-12 text-center" },
                    React.createElement(
                        "button",
                        { className: "btn btn-info", style: { width: "300px" }, onClick: this.actionFilter },
                        "Filtrar"
                    )
                )
            )
        );
    }
}