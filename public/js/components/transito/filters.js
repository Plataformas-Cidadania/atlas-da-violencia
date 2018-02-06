class Filters extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            type: null
        };
    }

    filterType(type) {
        console.log(type);
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
                            React.createElement(Type, { filterType: this.filterType })
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
                        { className: "btn btn-info", style: { width: "300px" } },
                        "Filtrar"
                    )
                )
            )
        );
    }
}