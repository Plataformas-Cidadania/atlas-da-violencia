class ListValoresSeries extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            valores: []
        };
    }

    componentDidMount() {
        this.loadValues();
    }

    loadValues() {
        $.ajax({
            method: 'GET',
            url: "valores-series/" + this.props.min + "/" + this.props.max,
            cache: false,
            success: function (data) {
                //console.log(data);
                this.setState({ valores: data });
                loadMap(data);
            }.bind(this),
            error: function (xhr, status, err) {
                console.log('erro');
            }.bind(this)
        });
    }

    render() {
        //let _this = this;
        let valores = this.state.valores.map(function (item, index) {
            return React.createElement(
                "tr",
                { key: index },
                React.createElement(
                    "th",
                    null,
                    item.uf
                ),
                React.createElement(
                    "td",
                    null,
                    item.total
                )
            );
        });

        return React.createElement(
            "table",
            { className: "table table-striped table-bordered" },
            React.createElement(
                "thead",
                null,
                React.createElement(
                    "tr",
                    null,
                    React.createElement(
                        "th",
                        null,
                        "Uf"
                    ),
                    React.createElement(
                        "th",
                        null,
                        "Valor"
                    )
                )
            ),
            React.createElement(
                "tbody",
                null,
                valores
            )
        );
    }
}

ReactDOM.render(React.createElement(ListValoresSeries, { min: "2004", max: "2006", valores: "" }), document.getElementById('listValoresSeries'));