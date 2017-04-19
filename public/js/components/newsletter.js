class Newsletter extends React.Component {
    constructor(props) {
        super(props);
        state = {
            nome: "",
            email: ""
        };

        this.submit = this.submit.bind(this);
        this.handleChande = this.handleChande.bind(this);
    }

    handleChande(event) {
        //console.log(event.target);
    }

    submit() {}

    render() {
        return React.createElement(
            "div",
            { className: "bg-pri box-news", "ng-class": "{'alto-contraste': altoContrasteAtivo}" },
            React.createElement(
                "div",
                { className: "container" },
                React.createElement("br", null),
                React.createElement(
                    "div",
                    { className: "row" },
                    React.createElement(
                        "div",
                        { className: "col-md-4" },
                        React.createElement(
                            "h3",
                            null,
                            "Newsletter"
                        ),
                        React.createElement(
                            "p",
                            null,
                            "Receba nossas novidades do portal e novos \xEDndices"
                        )
                    ),
                    React.createElement(
                        "div",
                        { className: "col-md-3" },
                        React.createElement("br", null),
                        React.createElement("br", null),
                        React.createElement("input", { type: "text", "class": "form-control", placeholder: "Nome", onChange: this.handleChande })
                    ),
                    React.createElement(
                        "div",
                        { className: "col-md-3" },
                        React.createElement("br", null),
                        React.createElement("br", null),
                        React.createElement("input", { type: "text", "class": "form-control", placeholder: "E-mail", onChange: this.handleChande })
                    ),
                    React.createElement(
                        "div",
                        { className: "col-md-2" },
                        React.createElement("br", null),
                        React.createElement(
                            "button",
                            { className: "btn btn-sec btn-padding" },
                            "Enviar"
                        )
                    )
                ),
                React.createElement("br", null)
            )
        );
    }
}