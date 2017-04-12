class ListValoresSeries extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            valores: []
        };
        //this.loadData = this.loadData.bind(this);
    }

    componentWillReceiveProps(props) {
        /*this.setState({min: props.min, max: props.max}, function(){
            this.loadData();
        });*/

        console.log(props.data);
        this.setState({ valores: props.data.valores });
    }

    /*loadData(){
        $.ajax({
            method:'GET',
            url: "valores-series/"+this.props.min+"/"+this.props.max,
            cache: false,
            success: function(data) {
                //console.log(data);
                this.setState({valores: data});
                //loadMap(data);
            }.bind(this),
            error: function(xhr, status, err) {
                console.log('erro');
            }.bind(this)
        });
    }*/

    render() {
        if (!this.state.valores) {
            return React.createElement(
                "h3",
                null,
                "Sem Resultados"
            );
        }

        let contColor = 0;

        let valores = this.state.valores.map(function (item, index) {

            if (contColor > colors2.length - 1) {
                contColor = 0;
            }

            let color = colors2[contColor];

            contColor++;

            return React.createElement(
                "tr",
                { key: index },
                React.createElement(
                    "th",
                    { width: "10px" },
                    React.createElement(
                        "i",
                        { className: "fa fa-square", style: { color: color } },
                        " "
                    )
                ),
                React.createElement(
                    "th",
                    null,
                    item.sigla,
                    " - ",
                    item.nome
                ),
                React.createElement(
                    "td",
                    { className: "text-right" },
                    formatNumber(item.valor, this.props.decimais, ',', '.')
                )
            );
        }.bind(this));

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
                        "\xA0"
                    ),
                    React.createElement(
                        "th",
                        null,
                        "Territ\xF3rio"
                    ),
                    React.createElement(
                        "th",
                        { className: "text-right" },
                        "Ocorr\xEAncias"
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