function Topico(props) {
    return React.createElement(
        "div",
        null,
        React.createElement("br", null),
        React.createElement(
            "div",
            { className: "row" },
            React.createElement(
                "div",
                { className: "col-md-12" },
                React.createElement(
                    "div",
                    { className: "icons-groups " + props.icon, style: { float: 'left' } },
                    "\xA0"
                ),
                React.createElement(
                    "h4",
                    { className: "icon-text" },
                    "\xA0\xA0",
                    props.text
                )
            )
        ),
        React.createElement("hr", { style: { borderColor: '#3498DB' } }),
        React.createElement("br", null)
    );
}

class PgSerie extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            id: this.props.id,
            serie: this.props.serie,
            unidade: this.props.unidade,
            loading: false,
            intervalos: [],
            valoresRegioesPorPeriodo: { min: 0, max: 0, values: {} },
            smallLarge: [0, 1],
            min: this.props.from,
            max: this.props.to,
            periodos: [],
            showMap: true,
            showCharts: true,
            showRegions: true,
            showTable: true,
            showCalcs: true,
            showInfo: true,
            chartLine: true,
            chartRadar: false,
            chartBar: false,
            chartPie: false
        };
        this.loading = this.loading.bind(this);
        this.changePeriodo = this.changePeriodo.bind(this);
        this.setPeriodos = this.setPeriodos.bind(this);
        this.showHide = this.showHide.bind(this);
        this.loadData = this.loadData.bind(this);
        this.setIntervalos = this.setIntervalos.bind(this);
        this.calcSmallLarge = this.calcSmallLarge.bind(this);
    }

    loading(status) {
        this.setState({ loading: status });
    }

    changePeriodo(min, max) {
        this.setState({ min: min, max: max }, function () {
            console.log(min, max);
            this.loadData();
        });
    }

    setPeriodos(periodos) {
        this.setState({ periodos: periodos });
    }

    loadData() {
        console.log(this.props.regions);
        $.ajax({
            method: 'GET',
            //url: "valores-regiao/"+this.state.id+"/"+this.props.tipoValores+"/"+this.state.min+"/"+this.state.max,
            url: "valores-regiao/" + this.state.id + "/" + this.state.min + "/" + this.state.max + "/" + this.props.regions + "/" + this.props.abrangencia,
            //url: "valores-regiao/"+this.state.id+"/"+this.state.max,
            cache: false,
            success: function (data) {
                console.log('pgSerie', data);
                //os valores menor e maior para serem utilizados no chartBar
                let smallLarge = this.calcSmallLarge(data.min.valores, data.max.valores);
                /*let totais = {
                    min: this.state.min,
                    max: this.state.max,
                    values: data
                };*/
                this.setState({ valoresRegioesPorPeriodo: data, smallLarge: smallLarge });

                ///////////////////////////////////////////////////////////
                ///////////////////////////////////////////////////////////
                /*let valores = [];
                for(let i in data.max.valores){
                    valores[i] = data.max.valores[i].valor;
                }
                //console.log('pgSerie', valores);
                let valoresOrdenados = valores.sort(function(a, b){
                    return a - b;
                });
                //console.log('pgSerie', valoresOrdenados);
                 intervalos = gerarIntervalos(valoresOrdenados);
                this.setIntervalos(intervalos);
                //console.log('pgSerie', intervalos);*/
                ///////////////////////////////////////////////////////////
                ///////////////////////////////////////////////////////////


                //loadMap(data);
            }.bind(this),
            error: function (xhr, status, err) {
                console.log('erro');
            }.bind(this)
        });
    }

    calcSmallLarge(minValores, maxValores) {
        let valores = [];
        minValores.find(function (item) {
            valores.push(parseFloat(item.valor));
        });
        maxValores.find(function (item) {
            valores.push(parseFloat(item.valor));
        });
        let valoresSort = valores.sort(function (a, b) {
            return a - b;
        });
        let smallLarge = [];
        smallLarge[0] = valoresSort[0] - 10;
        if (valoresSort[0] > 0 && valoresSort[0] < 10) {
            smallLarge[0] = valoresSort[0];
        }

        smallLarge[1] = valoresSort[valoresSort.length - 1];
        return smallLarge;
    }

    changeChart(chart) {
        this.setState({ chartLine: false, chartRadar: false, chartBar: false, chartPie: false }, function () {
            let chartChosen = {};
            chartChosen[chart] = true;
            this.setState(chartChosen);
        });
    }

    showHide(target) {
        let value = this.state['show' + target];
        let inverseValue = !value;
        this.setState({ ['show' + target]: inverseValue });
    }

    setIntervalos(intervalos) {
        this.setState({ intervalos: intervalos });
    }

    render() {

        //utilizado para função de formatação
        let decimais = this.state.unidade == 1 ? 0 : 2;

        return React.createElement(
            "div",
            null,
            React.createElement(
                "div",
                { className: "text-center", style: { display: this.state.loading ? 'block' : 'none' } },
                React.createElement("br", null),
                React.createElement("br", null),
                React.createElement(
                    "i",
                    { className: "fa fa-5x fa-spinner fa-spin" },
                    " "
                )
            ),
            React.createElement(
                "div",
                { style: { visibility: this.state.loading ? 'disable' : 'enable' } },
                React.createElement(
                    "div",
                    { className: "row" },
                    React.createElement(
                        "div",
                        { className: "col-md-6 h3", style: { margin: 0 } },
                        React.createElement("img", { style: { marginLeft: '5px' }, src: "imagens/links/8516-01.png", width: "52", alt: "", title: "" }),
                        "\xA0",
                        this.state.serie
                    ),
                    React.createElement(
                        "div",
                        { className: "col-md-6 text-right hidden-print" },
                        React.createElement(
                            "div",
                            { className: "icons-groups icon-group-print", onClick: () => window.print(),
                                style: { display: 'block', marginLeft: '5px' }, title: "" },
                            "\xA0"
                        ),
                        React.createElement(
                            "div",
                            { className: "icons-groups" + (this.state.showInfo ? " icon-group-info" : " icon-group-info-disable"),
                                style: { marginLeft: '5px' }, onClick: () => this.showHide('Info'), title: "" },
                            "\xA0"
                        ),
                        React.createElement(
                            "div",
                            { className: "icons-groups" + (this.state.showCalcs ? " icon-group-calc" : " icon-group-calc-disable"),
                                style: { marginLeft: '5px' }, onClick: () => this.showHide('Calcs'), title: "" },
                            "\xA0"
                        ),
                        React.createElement(
                            "div",
                            { className: "icons-groups" + (this.state.showTable ? " icon-group-table" : " icon-group-table-disable"),
                                style: { marginLeft: '5px' }, onClick: () => this.showHide('Table'), title: "" },
                            "\xA0"
                        ),
                        React.createElement(
                            "div",
                            { className: "icons-groups" + (this.state.showRegions ? " icon-group-rate" : " icon-group-rate-disable"),
                                style: { marginLeft: '5px' }, onClick: () => this.showHide('Regions'), title: "" },
                            "\xA0"
                        ),
                        React.createElement(
                            "div",
                            { className: "icons-groups" + (this.state.showCharts ? " icon-group-chart" : " icon-group-chart-disable"),
                                style: { marginLeft: '5px' }, onClick: () => this.showHide('Charts'), title: "" },
                            "\xA0"
                        ),
                        React.createElement(
                            "div",
                            { className: "icons-groups" + (this.state.showMap ? " icon-group-map" : " icon-group-map-disable"),
                                style: { marginLeft: '5px' }, onClick: () => this.showHide('Map'), title: "" },
                            "\xA0"
                        )
                    )
                ),
                React.createElement("br", null),
                React.createElement("div", { className: "line_title bg-pri" }),
                React.createElement(
                    "div",
                    { className: "hidden-print" },
                    React.createElement("br", null),
                    React.createElement(RangePeriodo, {
                        id: this.state.id,
                        changePeriodo: this.changePeriodo,
                        setPeriodos: this.setPeriodos,
                        loading: this.loading,
                        from: this.props.from,
                        to: this.props.to
                    }),
                    React.createElement("br", null)
                ),
                React.createElement(
                    "div",
                    { style: { display: this.state.showMap ? 'block' : 'none' } },
                    React.createElement(Topico, { icon: "icon-group-map", text: "Mapa" }),
                    React.createElement(
                        "div",
                        { className: "row" },
                        React.createElement(
                            "div",
                            { className: "col-md-6" },
                            React.createElement(Map, {
                                mapId: "map1",
                                id: this.state.id,
                                tipoValores: this.props.tipoValores,
                                decimais: decimais
                                /*min={this.state.min}
                                max={this.state.max}*/
                                , periodo: this.state.min,
                                setIntervalos: this.setIntervalos,
                                regions: this.props.regions,
                                abrangencia: this.props.abrangencia
                                /*typeRegion={this.props.typeRegion}
                                 typeRegionSerie={this.props.typeRegionSerie}*/
                            })
                        ),
                        React.createElement(
                            "div",
                            { className: "col-md-6" },
                            React.createElement(Map, {
                                mapId: "map2",
                                id: this.state.id,
                                tipoValores: this.props.tipoValores,
                                decimais: decimais
                                /*min={this.state.min}
                                 max={this.state.max}*/
                                , periodo: this.state.max,
                                setIntervalos: this.setIntervalos,
                                regions: this.props.regions,
                                abrangencia: this.props.abrangencia
                                /*typeRegion={this.props.typeRegion}
                                 typeRegionSerie={this.props.typeRegionSerie}*/
                            })
                        )
                    ),
                    React.createElement("br", null),
                    React.createElement("br", null),
                    React.createElement("br", null)
                ),
                React.createElement(
                    "div",
                    { style: { display: this.state.showCharts ? 'block' : 'none' } },
                    React.createElement(Topico, { icon: "icon-group-chart", text: "Gr\xE1ficos" }),
                    React.createElement(
                        "div",
                        null,
                        React.createElement(
                            "div",
                            { style: { textAlign: 'right' } },
                            React.createElement(
                                "div",
                                { className: "icons-charts" + (this.state.chartLine ? " icon-chart-line" : " icon-chart-line-disable"),
                                    style: { marginLeft: '5px' }, onClick: () => this.changeChart('chartLine'), title: "" },
                                "\xA0"
                            ),
                            React.createElement(
                                "div",
                                { className: "icons-charts" + (this.state.chartBar ? " icon-chart-bar" : " icon-chart-bar-disable"),
                                    style: { marginLeft: '5px' }, onClick: () => this.changeChart('chartBar'), title: "" },
                                "\xA0"
                            ),
                            React.createElement(
                                "div",
                                { className: "icons-charts" + (this.state.chartRadar ? " icon-chart-radar" : " icon-chart-radar-disable"),
                                    style: { marginLeft: '5px' }, onClick: () => this.changeChart('chartRadar'), title: "" },
                                "\xA0"
                            ),
                            React.createElement(
                                "div",
                                { className: "icons-charts" + (this.state.chartPie ? " icon-chart-pie" : " icon-chart-pie-disable"),
                                    style: { marginLeft: '5px', display: 'none' }, onClick: () => this.changeChart('chartPie'), title: "" },
                                "\xA0"
                            )
                        ),
                        React.createElement(
                            "div",
                            { style: { clear: 'both' } },
                            React.createElement("br", null)
                        ),
                        React.createElement(
                            "div",
                            { style: { display: this.state.chartLine ? 'block' : 'none' } },
                            React.createElement(ChartLine, {
                                id: this.state.id,
                                serie: this.state.serie,
                                min: this.state.min,
                                max: this.state.max,
                                periodos: this.state.periodos,
                                regions: this.props.regions,
                                abrangencia: this.props.abrangencia
                                /*typeRegion={this.props.typeRegion}
                                typeRegionSerie={this.props.typeRegionSerie}
                                intervalos={this.state.intervalos}*/
                            })
                        ),
                        React.createElement(
                            "div",
                            { style: { display: this.state.chartBar ? 'block' : 'none' } },
                            React.createElement(
                                "div",
                                { className: "row" },
                                React.createElement(
                                    "div",
                                    { className: "col-md-12" },
                                    React.createElement(ChartBar, {
                                        serie: this.state.serie,
                                        intervalos: this.state.intervalos,
                                        data: this.state.valoresRegioesPorPeriodo,
                                        smallLarge: this.state.smallLarge,
                                        idBar: "1"
                                    })
                                )
                            )
                        ),
                        React.createElement(
                            "div",
                            { style: { display: this.state.chartRadar ? 'block' : 'none' } },
                            React.createElement(ChartRadar, {
                                serie: this.state.serie,
                                data: this.state.valoresRegioesPorPeriodo.max
                            })
                        ),
                        React.createElement(
                            "div",
                            { style: { display: this.state.chartPie ? 'block' : 'none' } },
                            React.createElement(ChartPie, {
                                intervalos: this.state.intervalos,
                                data: this.state.valoresRegioesPorPeriodo.max
                            })
                        )
                    ),
                    React.createElement("br", null),
                    React.createElement("br", null)
                ),
                React.createElement(
                    "div",
                    { style: { display: this.state.showRegions && this.props.abrangencia == 3 ? 'block' : 'none' } },
                    React.createElement(Topico, { icon: "icon-group-rate", text: "Taxas" }),
                    React.createElement(Regions, {
                        id: this.state.id,
                        decimais: decimais,
                        regions: this.props.regions,
                        abrangencia: this.props.abrangencia,
                        min: this.state.min,
                        max: this.state.max,
                        data: this.state.valoresRegioesPorPeriodo.max
                    }),
                    React.createElement("br", null),
                    React.createElement("br", null)
                ),
                React.createElement(
                    "div",
                    { style: { display: this.state.showTable ? 'block' : 'none' } },
                    React.createElement(Topico, { icon: "icon-group-table", text: "Tabela" }),
                    React.createElement(
                        "div",
                        { style: { textAlign: 'center', clear: 'both' } },
                        React.createElement(
                            "button",
                            { className: "btn btn-primary btn-lg bg-pri", style: { border: '0' } },
                            this.state.max
                        ),
                        React.createElement(
                            "div",
                            { style: { marginTop: '-19px' } },
                            React.createElement("i", { className: "fa fa-sort-down fa-2x", style: { color: '#3498DB' } })
                        )
                    ),
                    React.createElement("br", null),
                    React.createElement(ListValoresSeries, {
                        decimais: decimais,
                        min: this.state.min,
                        max: this.state.max,
                        data: this.state.valoresRegioesPorPeriodo.max
                    }),
                    React.createElement("br", null),
                    React.createElement("br", null)
                ),
                React.createElement(
                    "div",
                    { className: "hidden-print", style: { display: this.state.showCalcs ? 'block' : 'none' } },
                    React.createElement(Topico, { icon: "icon-group-calc", text: "C\xE1lculos" }),
                    React.createElement(Calcs, {
                        id: this.state.id,
                        decimais: decimais,
                        serie: this.state.serie,
                        data: this.state.valoresRegioesPorPeriodo.max
                    }),
                    React.createElement("br", null),
                    React.createElement("br", null),
                    React.createElement("br", null)
                ),
                React.createElement(
                    "div",
                    { className: "hidden-print", style: { display: this.state.showInfo ? 'block' : 'none' } },
                    React.createElement(
                        "div",
                        { className: "row" },
                        React.createElement(
                            "div",
                            { className: "col-md-12" },
                            React.createElement(
                                "div",
                                { className: "icons-groups icon-group-info", style: { float: 'left' } },
                                "\xA0"
                            ),
                            React.createElement(
                                "h4",
                                { className: "icon-text" },
                                "\xA0\xA0Metadados"
                            )
                        )
                    ),
                    React.createElement("hr", { style: { borderColor: '#3498DB' } }),
                    React.createElement(
                        "div",
                        { className: "bs-callout", style: { borderLeftColor: '#3498DB' } },
                        React.createElement(
                            "p",
                            null,
                            this.props.metadados
                        )
                    )
                )
            )
        );
    }
}

ReactDOM.render(React.createElement(PgSerie, {
    id: serie_id,
    serie: serie,
    metadados: metadados,
    tipoValores: tipoValores,
    unidade: unidade,
    from: from,
    to: to,
    regions: regions,
    abrangencia: abrangencia
    /*typeRegion={typeRegion}
    typeRegionSerie={typeRegionSerie}*/
}), document.getElementById('pgSerie'));