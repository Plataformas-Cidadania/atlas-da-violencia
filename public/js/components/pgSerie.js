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
            fonte: this.props.fonte,
            loading: false,
            loadingItems: true,
            intervalos: [],
            intervalosFrom: [],
            intervalosTo: [],
            valoresRegioesPorPeriodo: { min: 0, max: 0, values: {} },
            valoresPeriodo: {},
            smallLarge: [0, 1],
            min: this.props.from,
            max: this.props.to,
            /*min: this.props.from,
            max: this.props.to,*/
            periodos: [],
            abrangencia: props.abrangencia,
            nomeAbrangencia: props.nomeAbrangencia,
            abrangenciasOk: props.abrangenciasOk,
            regions: props.regions,
            showMap: true,
            loadingMap: false,
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
        this.loadDataPeriodo = this.loadDataPeriodo.bind(this);
        this.loadDataMaps = this.loadDataMaps.bind(this);
        this.setIntervalos = this.setIntervalos.bind(this);
        this.calcSmallLarge = this.calcSmallLarge.bind(this);
        this.setAbrangencia = this.setAbrangencia.bind(this);
        this.setNomeAbrangencia = this.setNomeAbrangencia.bind(this);
        this.setRegions = this.setRegions.bind(this);
    }

    loading(status) {
        this.setState({ loading: status });
    }

    changePeriodo(min, max) {
        this.setState({ min: min, max: max }, function () {
            //console.log(min, max);
            this.loadData();
            this.loadDataPeriodo();
            this.loadDataMaps();
        });
    }

    setPeriodos(periodos) {
        this.setState({ periodos: periodos });
    }

    setAbrangencia(abrangencia) {
        $.ajax({
            method: 'GET',
            url: "get-regions/" + abrangencia,
            cache: false,
            success: function (data) {
                console.log('GET-REGIONS IN PGSERIE', data);
                this.setState({ regions: data, abrangencia: abrangencia });
            }.bind(this),
            error: function (xhr, status, err) {
                console.log('erro');
            }.bind(this)
        });
    }

    setNomeAbrangencia(nomeAbrangencia) {
        this.setState({ nomeAbrangencia: nomeAbrangencia });
    }

    setRegions(regions) {
        console.log(regions);
        this.setState({ regions: regions }, function () {
            this.loadData();
            this.loadDataPeriodo();
            this.loadDataMaps();
        });
    }

    loadData() {

        //console.log('MIN', this.state.min);
        //console.log('MAX', this.state.max);

        if (this.state.min && this.state.max) {

            this.setState({ loadingItems: true });

            //console.log(this.state.regions);
            $.ajax({
                method: 'GET',
                //url: "valores-regiao/"+this.state.id+"/"+this.props.tipoValores+"/"+this.state.min+"/"+this.state.max,
                url: "valores-regiao/" + this.state.id + "/" + this.state.min + "/" + this.state.max + "/" + this.state.regions + "/" + this.state.abrangencia,
                //url: "valores-regiao/"+this.state.id+"/"+this.state.max,
                cache: false,
                success: function (data) {
                    //console.log('pgSerie', data);
                    //os valores menor e maior para serem utilizados no chartBar
                    let smallLarge = this.calcSmallLarge(data.min.valores, data.max.valores);

                    this.setState({ valoresRegioesPorPeriodo: data, smallLarge: smallLarge, loadingItems: false });
                }.bind(this),
                error: function (xhr, status, err) {
                    console.log('erro');
                }.bind(this)
            });
        }
    }

    loadDataPeriodo() {
        if (this.state.min && this.state.max) {
            let _this = this;
            //$.ajax("periodo/"+this.state.id+"/"+this.state.min+"/"+this.state.max, {
            $.ajax("periodo/" + this.state.id + "/" + this.state.min + "/" + this.state.max + "/" + this.state.regions + "/" + this.state.abrangencia, {
                data: {},
                success: function (data) {
                    this.setState({ valoresPeriodo: data });
                }.bind(this),
                error: function (data) {
                    console.log('erro');
                }.bind(this)
            });
        }
    }

    loadDataMaps() {
        this.setState({ loadingMap: true });
        if (this.state.min && this.state.min) {
            let _this = this;
            $.ajax("regiao/" + _this.state.id + "/" + _this.state.min + "/" + _this.state.regions + "/" + _this.state.abrangencia, {
                data: {},
                success: function (dataMapFrom) {

                    let valoresMapFrom = this.getValoresMap(dataMapFrom);

                    $.ajax("regiao/" + _this.state.id + "/" + _this.state.max + "/" + _this.state.regions + "/" + _this.state.abrangencia, {
                        data: {},
                        success: function (dataMapTo) {

                            let valoresMapTo = this.getValoresMap(dataMapTo);

                            let intervalos = this.setIntervalos(gerarIntervalos(valoresMapFrom), gerarIntervalos(valoresMapTo));
                            //console.log(dataMapFrom);
                            //console.log(dataMapTo);
                            //console.log(intervalos);
                            this.setState({ dataMapFrom: dataMapFrom, dataMapTo: dataMapTo, intervalos: intervalos, loadingMap: false });
                        }.bind(this),
                        error: function (data) {
                            //console.log('map', 'erro');
                        }
                    });
                }.bind(this),
                error: function (data) {
                    //console.log('map', 'erro');
                }
            });
        }
    }

    getValoresMap(data) {
        let valores = [];
        for (let i in data.features) {
            valores[i] = data.features[i].properties.total;
        }

        return valores;
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

    setIntervalos(intervalosFrom, intervalosTo) {

        if (intervalosFrom[intervalosFrom.length - 1] > intervalosTo[intervalosTo.length - 1]) {
            return intervalosFrom;
        }

        return intervalosTo;
    }

    change(event) {
        console.log(event.target.value);
    }

    generateArrayYears(from, to) {
        let arrayYears = [];
        for (let i = from; i <= to; i++) {
            arrayYears.push(i);
        }

        return arrayYears;
    }

    render() {

        //utilizado para função de formatação
        let decimais = this.props.tipoUnidade == 1 ? 0 : 2;

        let regions = null;

        let from = formatPeriodicidade(this.state.min, this.props.periodicidade);
        let to = formatPeriodicidade(this.state.max, this.props.periodicidade);

        let arrayYears = this.generateArrayYears(from, to);

        let optionsDownloadPeriodosFrom = arrayYears.map(function (item, index) {
            let selected = false;
            if (index == 0) {
                selected = 'selected';
            }
            return React.createElement(
                "option",
                { selected: selected, value: item + '-01-' + '-01' },
                item
            );
        });

        let optionsDownloadPeriodosTo = arrayYears.map(function (item, index) {
            let selected = false;
            if (index == arrayYears.length - 1) {
                selected = 'selected';
            }
            return React.createElement(
                "option",
                { selected: selected, value: item + '-01-' + '-01' },
                item
            );
        }.bind(this));

        if (this.state.showRegions && this.state.abrangencia == 3) {
            regions = React.createElement(
                "div",
                { style: { display: this.state.showRegions && this.state.abrangencia == 3 ? 'block' : 'none' } },
                React.createElement(Topico, { icon: "icon-group-rate", text: "Taxas" }),
                React.createElement(Regions, {
                    id: this.state.id,
                    periodicidade: this.props.periodicidade,
                    decimais: decimais,
                    regions: this.state.regions,
                    abrangencia: this.state.abrangencia,
                    min: this.state.min,
                    max: this.state.max,
                    data: this.state.valoresRegioesPorPeriodo.max
                }),
                React.createElement("br", null),
                React.createElement("br", null)
            );
        }

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
                        { className: "h3" },
                        "\xA0",
                        this.state.serie
                    ),
                    React.createElement("div", { className: "line_title bg-pri" }),
                    React.createElement("br", null),
                    React.createElement(
                        "div",
                        { className: "col-md-6" },
                        React.createElement(AbrangenciaSerie, {
                            abrangencia: this.state.abrangencia,
                            nomeAbrangencia: this.state.nomeAbrangencia,
                            setAbrangencia: this.setAbrangencia,
                            abrangenciasOk: this.state.abrangenciasOk,
                            setRegions: this.setRegions,
                            setNomeAbrangencia: this.setNomeAbrangencia
                        })
                    ),
                    React.createElement(
                        "div",
                        { className: "col-md-6 text-right hidden-print" },
                        React.createElement(
                            "div",
                            { className: "dropdown" },
                            React.createElement(
                                "div",
                                { id: "dLabel", className: "icons-groups icon-group-download", "data-toggle": "dropdown", "aria-haspopup": "true", "aria-expanded": "false",
                                    style: { display: 'block', marginLeft: '5px' }, title: "" },
                                "\xA0"
                            ),
                            React.createElement(
                                "ul",
                                { className: "dropdown-menu", "aria-labelledby": "dLabel", style: { left: 'inherit', right: '0', float: 'right', margin: '40px 0 0' } },
                                React.createElement(
                                    "li",
                                    null,
                                    React.createElement(
                                        "a",
                                        null,
                                        "Dados em .csv"
                                    )
                                ),
                                React.createElement("li", { role: "separator", className: "divider" }),
                                React.createElement(
                                    "li",
                                    null,
                                    React.createElement(
                                        "form",
                                        { name: "frmDownloadPeriodo", action: "download-dados", target: "_blank", method: "POST" },
                                        React.createElement("input", { type: "hidden", name: "_token", value: $('meta[name="csrf-token"]').attr('content') }),
                                        React.createElement("input", { type: "hidden", name: "downloadType", value: "csv" }),
                                        React.createElement("input", { type: "hidden", name: "id", value: this.props.id }),
                                        React.createElement("input", { type: "hidden", name: "serie", value: this.props.serie }),
                                        React.createElement("input", { type: "hidden", name: "from", value: this.state.min }),
                                        React.createElement("input", { type: "hidden", name: "to", value: this.state.max }),
                                        React.createElement("input", { type: "hidden", name: "regions", value: this.state.regions }),
                                        React.createElement("input", { type: "hidden", name: "abrangencia", value: this.state.abrangencia }),
                                        React.createElement(
                                            "button",
                                            { className: "btn-download" },
                                            "Download (",
                                            formatPeriodicidade(this.state.min, this.props.periodicidade),
                                            " - ",
                                            formatPeriodicidade(this.state.max, this.props.periodicidade),
                                            ")"
                                        )
                                    )
                                ),
                                React.createElement(
                                    "li",
                                    null,
                                    React.createElement(
                                        "form",
                                        { name: "frmDownloadTotal", action: "download-dados", target: "_blank", method: "POST" },
                                        React.createElement("input", { type: "hidden", name: "_token", value: $('meta[name="csrf-token"]').attr('content') }),
                                        React.createElement("input", { type: "hidden", name: "downloadType", value: "csv" }),
                                        React.createElement("input", { type: "hidden", name: "id", value: this.props.id }),
                                        React.createElement("input", { type: "hidden", name: "serie", value: this.props.serie }),
                                        React.createElement("input", { type: "hidden", name: "regions", value: this.state.regions }),
                                        React.createElement("input", { type: "hidden", name: "abrangencia", value: this.state.abrangencia }),
                                        React.createElement(
                                            "button",
                                            { className: "btn-download" },
                                            "Download Total"
                                        )
                                    )
                                ),
                                React.createElement(
                                    "li",
                                    null,
                                    React.createElement(
                                        "button",
                                        { className: "btn-download", "data-toggle": "modal", "data-target": "#downloadModal" },
                                        "Download Personalizado"
                                    )
                                ),
                                React.createElement("br", null),
                                React.createElement(
                                    "li",
                                    null,
                                    React.createElement(
                                        "a",
                                        null,
                                        "Dados em .ods"
                                    )
                                ),
                                React.createElement("li", { role: "separator", className: "divider" }),
                                React.createElement(
                                    "li",
                                    null,
                                    React.createElement(
                                        "form",
                                        { name: "frmDownloadPeriodo", action: "download-dados", target: "_blank", method: "POST" },
                                        React.createElement("input", { type: "hidden", name: "_token", value: $('meta[name="csrf-token"]').attr('content') }),
                                        React.createElement("input", { type: "hidden", name: "downloadType", value: "ods" }),
                                        React.createElement("input", { type: "hidden", name: "id", value: this.props.id }),
                                        React.createElement("input", { type: "hidden", name: "serie", value: this.props.serie }),
                                        React.createElement("input", { type: "hidden", name: "from", value: this.state.min }),
                                        React.createElement("input", { type: "hidden", name: "to", value: this.state.max }),
                                        React.createElement("input", { type: "hidden", name: "regions", value: this.state.regions }),
                                        React.createElement("input", { type: "hidden", name: "abrangencia", value: this.state.abrangencia }),
                                        React.createElement(
                                            "button",
                                            { className: "btn-download" },
                                            "Download (",
                                            formatPeriodicidade(this.state.min, this.props.periodicidade),
                                            " - ",
                                            formatPeriodicidade(this.state.max, this.props.periodicidade),
                                            ")"
                                        )
                                    )
                                ),
                                React.createElement(
                                    "li",
                                    null,
                                    React.createElement(
                                        "form",
                                        { name: "frmDownloadTotal", action: "download-dados", target: "_blank", method: "POST" },
                                        React.createElement("input", { type: "hidden", name: "_token", value: $('meta[name="csrf-token"]').attr('content') }),
                                        React.createElement("input", { type: "hidden", name: "downloadType", value: "ods" }),
                                        React.createElement("input", { type: "hidden", name: "id", value: this.props.id }),
                                        React.createElement("input", { type: "hidden", name: "serie", value: this.props.serie }),
                                        React.createElement("input", { type: "hidden", name: "regions", value: this.state.regions }),
                                        React.createElement("input", { type: "hidden", name: "abrangencia", value: this.state.abrangencia }),
                                        React.createElement(
                                            "button",
                                            { className: "btn-download" },
                                            "Download Total"
                                        )
                                    )
                                )
                            ),
                            React.createElement(
                                "div",
                                { id: "downloadModal", className: "modal fade text-left", role: "dialog", style: { zIndex: "9999999999" } },
                                React.createElement(
                                    "div",
                                    { className: "modal-dialog" },
                                    React.createElement(
                                        "div",
                                        { className: "modal-content" },
                                        React.createElement(
                                            "form",
                                            { name: "frmDownloadTotal", action: "download-dados", target: "_blank", method: "POST" },
                                            React.createElement(
                                                "div",
                                                { className: "modal-header" },
                                                React.createElement(
                                                    "button",
                                                    { type: "button", className: "close",
                                                        "data-dismiss": "modal" },
                                                    "\xD7"
                                                ),
                                                React.createElement(
                                                    "h4",
                                                    { className: "modal-title" },
                                                    "Personalizar Download"
                                                )
                                            ),
                                            React.createElement(
                                                "div",
                                                { className: "modal-body" },
                                                React.createElement("input", { type: "hidden", name: "_token", value: $('meta[name="csrf-token"]').attr('content') }),
                                                React.createElement("input", { type: "hidden", name: "downloadType", value: "csv" }),
                                                React.createElement("input", { type: "hidden", name: "id", value: this.props.id }),
                                                React.createElement("input", { type: "hidden", name: "serie", value: this.props.serie }),
                                                React.createElement(
                                                    "div",
                                                    null,
                                                    React.createElement(
                                                        "label",
                                                        { htmlFor: "decimal" },
                                                        "Separador Decimal"
                                                    ),
                                                    React.createElement(
                                                        "select",
                                                        { name: "decimal", className: "form-control" },
                                                        React.createElement(
                                                            "option",
                                                            { value: "," },
                                                            ","
                                                        ),
                                                        React.createElement(
                                                            "option",
                                                            { value: "." },
                                                            "."
                                                        )
                                                    )
                                                ),
                                                React.createElement(
                                                    "div",
                                                    null,
                                                    React.createElement(
                                                        "label",
                                                        { htmlFor: "from" },
                                                        "De"
                                                    ),
                                                    React.createElement(
                                                        "select",
                                                        { name: "from", className: "form-control" },
                                                        optionsDownloadPeriodosFrom
                                                    )
                                                ),
                                                React.createElement(
                                                    "div",
                                                    null,
                                                    React.createElement(
                                                        "label",
                                                        { htmlFor: "to" },
                                                        "At\xE9"
                                                    ),
                                                    React.createElement(
                                                        "select",
                                                        { name: "to", className: "form-control" },
                                                        optionsDownloadPeriodosTo
                                                    )
                                                ),
                                                React.createElement("input", { type: "hidden", name: "regions", value: this.state.regions }),
                                                React.createElement("input", { type: "hidden", name: "abrangencia", value: this.state.abrangencia })
                                            ),
                                            React.createElement(
                                                "div",
                                                { className: "modal-footer" },
                                                React.createElement(
                                                    "button",
                                                    { type: "button", className: "btn btn-default",
                                                        "data-dismiss": "modal" },
                                                    "Fechar"
                                                ),
                                                React.createElement(
                                                    "button",
                                                    { className: "btn btn-primary" },
                                                    "Download"
                                                )
                                            )
                                        )
                                    )
                                )
                            )
                        ),
                        React.createElement(
                            "div",
                            { className: "icons-groups icon-group-email", "data-toggle": "modal", "data-target": "#myModal",
                                style: { display: 'block', marginLeft: '5px' }, title: "" },
                            "\xA0"
                        ),
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
                            { className: "icons-groups" + (this.state.showTable ? " icon-group-table" : " icon-group-table-disable"),
                                style: { marginLeft: '5px' }, onClick: () => this.showHide('Table'), title: "" },
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
                React.createElement(
                    "div",
                    { className: "hidden-print" },
                    React.createElement("br", null),
                    React.createElement(RangePeriodo, {
                        id: this.state.id,
                        periodicidade: this.props.periodicidade,
                        abrangencia: this.state.abrangencia,
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
                    { style: { borderTop: 'solid 1px #ccc', padding: '10px 0' }, className: "text-right" },
                    React.createElement("div", { style: { clear: 'both' } })
                ),
                React.createElement(
                    "div",
                    { style: { display: this.state.showTable ? 'block' : 'none' } },
                    React.createElement(Topico, { icon: "icon-group-table", text: "Tabela" }),
                    React.createElement(
                        "div",
                        { style: { display: this.state.loadingItems ? '' : 'none' }, className: "text-center" },
                        React.createElement("i", { className: "fa fa-spin fa-spinner fa-4x" })
                    ),
                    React.createElement(
                        "div",
                        { style: { display: this.state.loadingItems ? 'none' : '' } },
                        React.createElement(ListValoresSeries, {
                            decimais: decimais,
                            periodicidade: this.props.periodicidade,
                            nomeAbrangencia: this.state.nomeAbrangencia,
                            min: this.state.min,
                            max: this.state.max,
                            data: this.state.valoresPeriodo,
                            tipoUnidade: this.props.tipoUnidade,
                            abrangencia: this.state.abrangencia
                            /*data={this.state.valoresRegioesPorPeriodo.max}*/
                            /*dataMin={this.state.valoresRegioesPorPeriodo.min}
                            dataMax={this.state.valoresRegioesPorPeriodo.max}*/
                        }),
                        React.createElement(
                            "p",
                            { style: { marginTop: '-50px' } },
                            React.createElement(
                                "strong",
                                null,
                                "Unidade: "
                            ),
                            this.props.unidade
                        )
                    ),
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
                                periodicidade: this.props.periodicidade,
                                min: this.state.min,
                                max: this.state.max,
                                periodos: this.state.periodos,
                                regions: this.state.regions,
                                abrangencia: this.state.abrangencia
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
                                        id: this.state.id,
                                        serie: this.state.serie,
                                        periodicidade: this.props.periodicidade
                                        /*intervalos={this.state.intervalos}*/
                                        , min: this.state.min,
                                        max: this.state.max,
                                        regions: this.state.regions,
                                        abrangencia: this.state.abrangencia
                                        /*data={this.state.valoresRegioesPorPeriodo}*/
                                        /*smallLarge={this.state.smallLarge}*/
                                        , idBar: "1"
                                    })
                                )
                            )
                        ),
                        React.createElement(
                            "div",
                            { style: { display: this.state.chartRadar ? 'block' : 'none' } },
                            React.createElement(ChartRadar, {
                                serie: this.state.serie,
                                periodicidade: this.props.periodicidade,
                                data: this.state.valoresRegioesPorPeriodo.max
                            })
                        ),
                        React.createElement(
                            "div",
                            { style: { display: this.state.chartPie ? 'block' : 'none' } },
                            React.createElement(ChartPie, {
                                intervalos: this.state.intervalos,
                                periodicidade: this.props.periodicidade,
                                data: this.state.valoresRegioesPorPeriodo.max
                            })
                        )
                    ),
                    React.createElement("br", null),
                    React.createElement("br", null)
                ),
                regions,
                React.createElement(
                    "div",
                    { style: { display: this.state.showMap ? 'block' : 'none' } },
                    React.createElement(Topico, { icon: "icon-group-map", text: "Mapa" }),
                    React.createElement(
                        "div",
                        { className: "row col-md-12 text-center", style: { display: this.state.loadingMap ? 'block' : 'none' } },
                        React.createElement("i", { className: "fa fa-spin fa-spinner fa-4x" })
                    ),
                    React.createElement(
                        "div",
                        { className: "row", style: { display: !this.state.loadingMap ? 'block' : 'none' } },
                        React.createElement(
                            "div",
                            { className: "col-md-6 col-sm-12" },
                            React.createElement(Map, {
                                mapId: "map1",
                                id: this.state.id,
                                serie: this.props.serie,
                                periodicidade: this.props.periodicidade,
                                tipoValores: this.props.tipoValores,
                                decimais: decimais
                                /*min={this.state.min}
                                max={this.state.max}*/
                                , data: this.state.dataMapFrom,
                                periodo: this.state.min
                                //tipoPeriodo="from"
                                , intervalos: this.state.intervalos
                                //setIntervalos={this.setIntervalos}
                                //regions={this.state.regions}
                                //abrangencia={this.state.abrangencia}
                                /*typeRegion={this.props.typeRegion}
                                 typeRegionSerie={this.props.typeRegionSerie}*/
                            })
                        ),
                        React.createElement(
                            "div",
                            { className: "col-md-6 col-sm-12 print-map" },
                            React.createElement(Map, {
                                mapId: "map2",
                                id: this.state.id,
                                serie: this.props.serie,
                                periodicidade: this.props.periodicidade,
                                tipoValores: this.props.tipoValores,
                                decimais: decimais
                                /*min={this.state.min}
                                 max={this.state.max}*/
                                , data: this.state.dataMapTo,
                                periodo: this.state.max
                                //tipoPeriodo="to"
                                , intervalos: this.state.intervalos
                                //setIntervalos={this.setIntervalos}
                                //regions={this.state.regions}
                                //abrangencia={this.state.abrangencia}
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
                        React.createElement("div", { dangerouslySetInnerHTML: { __html: this.props.metadados } }),
                        React.createElement("br", null),
                        React.createElement(
                            "div",
                            { className: "text-right" },
                            React.createElement(
                                "a",
                                { href: "downloads/" + this.props.id + "/" + this.props.serie, className: "text-info h5" },
                                React.createElement(
                                    "strong",
                                    null,
                                    "+ informa\xE7\xF5es"
                                )
                            )
                        )
                    ),
                    React.createElement(
                        "p",
                        null,
                        React.createElement(
                            "strong",
                            null,
                            "Fonte: "
                        ),
                        this.props.fonte
                    )
                )
            )
        );
    }
}

ReactDOM.render(React.createElement(PgSerie, {
    id: serie_id,
    serie: serie,
    periodicidade: periodicidade,
    metadados: metadados,
    fonte: fonte,
    tipoValores: tipoValores,
    unidade: unidade,
    tipoUnidade: tipoUnidade,
    from: from,
    to: to,
    regions: regions,
    abrangencia: abrangencia,
    abrangenciasOk: abrangenciasOk,
    nomeAbrangencia: nomeAbrangencia
    /*typeRegion={typeRegion}
    typeRegionSerie={typeRegionSerie}*/
}), document.getElementById('pgSerie'));