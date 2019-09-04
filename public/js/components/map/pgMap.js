class PgMap extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            dataMap: [],
            intervalos: [],
            loadingMap: true
        };

        this.loadDataMaps = this.loadDataMaps.bind(this);
    }

    componentDidMount() {
        this.loadDataMaps();
    }

    loadDataMaps() {
        console.log(this.state);
        console.log(this.props);
        this.setState({ loadingMap: true });
        let _this = this;
        $.ajax("regiao/" + _this.props.serie_id + "/" + _this.props.periodo + "/" + _this.props.regions + "/" + _this.props.abrangencia, {
            data: {},
            success: function (dataMap) {

                let valoresMap = this.getValoresMap(dataMap);
                let qtdValores = valoresMap.length;
                let qtdIntervalos = 10;
                if (qtdValores < 10) {
                    qtdIntervalos = qtdValores;
                }

                let intervalos = gerarIntervalos(valoresmap[0], valoresMap[valoresMap.length - 1], qtdIntervalos);

                this.setState({ dataMap: dataMap, intervalos: intervalos, loadingMap: false });
            }.bind(this),
            error: function (data) {
                //console.log('map', 'erro');
            }
        });
    }

    getValoresMap(data) {
        let valores = [];
        for (let i in data.features) {
            valores[i] = data.features[i].properties.total;
        }

        return valores;
    }

    render() {

        let decimais = this.props.tipoUnidade == 1 ? 0 : 2;

        return React.createElement(
            "div",
            null,
            React.createElement(Map, {
                mapId: "map1",
                id: this.props.serie_id,
                serie: this.props.serie,
                periodicidade: this.props.periodicidade,
                tipoValores: "",
                decimais: decimais,
                data: this.state.dataMap,
                periodo: this.props.periodo,
                intervalos: this.state.intervalos,
                lang_mouse_over_region: this.props.lang_mouse_over_region
            })
        );
    }

}

ReactDOM.render(React.createElement(PgMap, {
    serie_id: serie_id,
    serie: serie,
    tipoUnidade: tipoUnidade,
    periodo: periodo,
    regions: regions,
    abrangencia: abrangencia,
    lang_mouse_over_region: lang_mouse_over_region
}), document.getElementById('map'));