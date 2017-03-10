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

        this.setState({ valores: props.data.values });
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
        if (!this.state.valores.length) {
            return React.createElement(
                "h3",
                null,
                "Sem Resultados"
            );
        }
        //console.log('========================================================');
        let valores = this.state.valores.map(function (item, index) {
            return React.createElement(
                "tr",
                { key: index },
                React.createElement(
                    "th",
                    { width: "10px" },
                    React.createElement(
                        "i",
                        { className: "fa fa-square", style: { color: getColor(item.total, intervalos) } },
                        " "
                    )
                ),
                React.createElement(
                    "th",
                    null,
                    item.uf,
                    " - ",
                    item.nome
                ),
                React.createElement(
                    "td",
                    { className: "text-right" },
                    numeral(item.total).format('0.00')
                )
            );
        });
        //console.log('========================================================');

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
                        "Uf"
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