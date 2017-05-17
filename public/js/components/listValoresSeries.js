class ListValoresSeries extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            valores: []
        };
        //this.loadData = this.loadData.bind(this);
    }

    componentDidMount() {
        //this.loadData();
    }

    componentWillReceiveProps(props) {
        /*this.setState({min: props.min, max: props.max}, function(){
         this.loadData();
         });*/

        //console.log(props.data);
        this.setState({ valores: props.data.valores });
    }

    /*loadData(){
        $.ajax({
            method:'GET',
            url: "periodo/"+this.state.id+"/"+this.state.min+"/"+this.state.max+"/"+this.props.regions+"/"+this.props.abrangencia,
            cache: false,
            success: function(data) {
                console.log('listValoresSeries', data);
                 let valores = [];
                for(let i in data){
                    let region = {};
                    region[i] = data[i];
                    valores.push(region);
                }
                 this.setState({valores: valores});
            }.bind(this),
            error: function(xhr, status, err) {
              console.log('erro', err);
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

            //para que no municipio não aparece repetido o nome
            let sigla = null;
            if (item.sigla !== item.nome) {
                sigla = item.sigla + ' - ';
            }

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
                    sigla,
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
            "div",
            null,
            React.createElement(
                "table",
                { className: "table table-striped table-bordered", id: "listValoresSeries" },
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
            ),
            React.createElement("br", null),
            React.createElement(
                "div",
                { style: { float: 'right', marginLeft: '5px' } },
                React.createElement(Download, { btnDownload: "downloadListValoresSeries", divDownload: "listValoresSeries", arquivo: "tabela.png" })
            ),
            React.createElement(
                "div",
                { style: { float: 'right', marginLeft: '5px' } },
                React.createElement(Print, { divPrint: "listValoresSeries", imgPrint: "imgPrintList" })
            ),
            React.createElement("div", { style: { clear: 'both' } })
        );
    }
}