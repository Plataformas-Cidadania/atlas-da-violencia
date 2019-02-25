class Page extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            filters: [],
            idTypes: [],
            idTypesAccident: [],
            idGender: [],
            tipoTerritorioSelecionado: 2, //1 - país, 2 - regiao, 3 - uf, 4 - municipio
            codigoTerritorioSelecionado: props.default_regions, //203 - Brasil 13 - SE
            tipoTerritorioAgrupamento: 2, //1 - país, 2 - regiao, 3 - uf, 4 - municipio
            typeIcons: ['outros.png', 'automovel.png', 'motocicleta.png', 'pedestre.png', 'onibus.png', 'caminhao.png', 'bicicleta.png', 'outros.png'],
            iconsType: [],
            filter: 0,
            year: null,
            month: null,
            start: null,
            end: null,
            months: { 'Jan': '01', 'Fev': '02', 'Mar': '03', 'Abr': '04', 'Mai': '05', 'Jun': '06', 'Jul': '07', 'Ago': '08', 'Set': '09', 'Out': '10', 'Nov': '11', 'Dez': '12' },
            valuesForTypes: [],
            selectedTypes: [],
            valuesForGender: [],
            valuesForRegions: [],
            valuesForUfs: [],
            valuesForChartFilters: [],
            values: [],
            currentPageListItems: 1

        };

        //this.convertToArrayDefaultRegions = this.convertToArrayDefaultRegions.bind(this);
        this.checkFilter = this.checkFilter.bind(this);
        this.checkType = this.checkType.bind(this);
        this.checkTypeAccident = this.checkTypeAccident.bind(this);
        this.checkGender = this.checkGender.bind(this);
        this.checkRegion = this.checkRegion.bind(this);
        this.checkYear = this.checkYear.bind(this);
        this.checkMonth = this.checkMonth.bind(this);
        this.actionFilter = this.actionFilter.bind(this);
        this.mountPer = this.mountPer.bind(this);
        this.lastDayMonth = this.lastDayMonth.bind(this);
        this.loadValuesForTypes = this.loadValuesForTypes.bind(this);
        this.loadValuesForGender = this.loadValuesForGender.bind(this);
        this.iconsType = this.iconsType.bind(this);
        this.setCurrentPageListItems = this.setCurrentPageListItems.bind(this);
        this.loadValues = this.loadValues.bind(this);
        this.loadValuesForRegions = this.loadValuesForRegions.bind(this);
        this.loadValuesForUfs = this.loadValuesForUfs.bind(this);
        this.loadValuesChartFilters = this.loadValuesChartFilters.bind(this);
    }

    componentDidMount() {
        //this.convertToArrayDefaultRegions();
        console.log('CODIGO TERRITORIO SELECIONADO', this.state.codigoTerritorioSelecionado);
    }

    /*convertToArrayDefaultRegions(){
        let default_regions = this.props.default_regions.split(',');
        let codigoTerritorioSelecionado = this.state.codigoTerritorioSelecionado;
        default_regions.find(function(item){
            codigoTerritorioSelecionado.push(item)
        }.bind(this));
        this.setState({codigoTerritorioSelecionado: codigoTerritorioSelecionado}, function(){
            console.log('CODIGO TERRITORIO SELECIONADO', this.state.codigoTerritorioSelecionado);
        });
    }*/

    mountPer() {
        let start = this.state.year + '-' + this.state.months[this.state.month] + '-01';
        let end = this.state.year + '-' + this.state.months[this.state.month] + '-' + this.lastDayMonth(this.state.month);
        this.setState({ start: start, end: end }, function () {
            //this.loadMap();
            //this.loadDataTotalPorTerritorio();
            this.loadValuesForTypes();
            this.loadValuesForGender();
            this.loadValuesForRegions();
            this.loadValuesForUfs();
            this.loadValues();
            this.loadValuesChartFilters();
        });
    }

    lastDayMonth(month) {
        let arrayLastDay = { '01': '31', '02': '29', '03': '31', '04': '30', '05': '31', '06': '30', '07': '31', '08': '31', '09': '30', '10': '31', '11': '30', '12': '31' };
        let months = this.state.months;
        //console.log(month);
        return arrayLastDay[months[month]];
    }

    checkFilter(filters) {
        this.setState({ filters: filters });
    }

    checkType(types) {
        let ids = [];
        types.find(function (item) {
            ids.push(item.id);
        });
        this.setState({ idTypes: ids });
    }

    checkTypeAccident(types) {
        let ids = [];
        types.find(function (item) {
            ids.push(item.id);
        });
        this.setState({ idTypesAccident: ids });
    }

    checkGender(types) {
        let ids = [];
        types.find(function (item) {
            ids.push(item.id);
        });
        this.setState({ idGender: ids });
    }

    checkYear(year) {
        this.setState({ year: year }, function () {
            if (this.state.year != null && this.state.month != null) {
                this.mountPer();
            }
        });
    }

    checkMonth(month) {
        this.setState({ month: month }, function () {
            if (this.state.year != null && this.state.month != null) {
                this.mountPer();
            }
        });
    }

    checkRegion(types) {
        let ids = [];
        let tipo_territorio = null;
        types.find(function (item) {
            ids.push(parseInt(item.id));
            tipo_territorio = parseInt(item.tipo_territorio);
        });
        this.setState({ codigoTerritorioSelecionado: ids, tipoTerritorioSelecionado: tipo_territorio, tipoTerritorioAgrupamento: tipo_territorio });
    }

    actionFilter() {
        this.setState({ filter: 1 }, function () {
            this.setState({ filter: 0 });
            this.loadValuesForTypes();
            this.loadValuesForGender();
            this.loadValuesForRegions();
            this.loadValuesForUfs();
            this.loadValues();
            this.loadValuesChartFilters();
        });
    }

    iconsType(icons) {
        //console.log(icons);
        this.setState({ iconsType: icons });
    }

    loadValuesChartFilters() {
        $.ajax({
            method: 'POST',
            url: 'values-chart-filters',
            data: {
                serie_id: this.props.id,
                start: this.state.start,
                end: this.state.end,
                filters: this.state.filters
            },
            cache: false,
            success: function (data) {
                //console.log(data);

                this.setState({ valuesForChartFilters: data, loading: false });
            }.bind(this),
            error: function (xhr, status, err) {
                console.error(status, err.toString());
                this.setState({ loading: false });
            }.bind(this)
        });
    }

    loadValuesForTypes() {

        if (!this.state.start || !this.state.end) {
            return;
        }

        $.ajax({
            method: 'POST',
            url: "values-for-types",
            data: {
                id: this.props.id,
                start: this.state.start,
                end: this.state.end,
                typesAccident: this.state.idTypesAccident,
                filters: this.state.filters,
                genders: this.state.idGender,
                tipoTerritorioSelecionado: this.state.tipoTerritorioSelecionado, // tipo de territorio selecionado
                codigoTerritorioSelecionado: this.state.codigoTerritorioSelecionado //codigo do territorio, que pode ser codigo do país, regiao, uf, etc...
            },
            cache: false,
            success: function (data) {
                //console.log('values-for-types', data);
                this.setState({ valuesForTypes: data });
            }.bind(this),
            error: function (xhr, status, err) {
                console.log('erro');
            }.bind(this)
        });
    }

    loadValuesForGender() {

        if (!this.state.start || !this.state.end) {
            return;
        }

        $.ajax({
            method: 'POST',
            url: "values-for-gender",
            data: {
                id: this.props.id,
                start: this.state.start,
                end: this.state.end,
                types: this.state.idTypes,
                typesAccident: this.state.idTypesAccident,
                tipoTerritorioSelecionado: this.state.tipoTerritorioSelecionado, // tipo de territorio selecionado
                codigoTerritorioSelecionado: this.state.codigoTerritorioSelecionado //codigo do territorio, que pode ser codigo do país, regiao, uf, etc...
            },
            cache: false,
            success: function (data) {
                //console.log('values-for-gender', data);
                this.setState({ valuesForGender: data });
            }.bind(this),
            error: function (xhr, status, err) {
                console.log('erro');
            }.bind(this)
        });
    }

    loadValuesForRegions() {

        if (!this.state.start || !this.state.end) {
            return;
        }

        $.ajax({
            method: 'POST',
            url: "values-for-regions",
            data: {
                id: this.props.id,
                start: this.state.start,
                end: this.state.end,
                types: this.state.idTypes,
                typesAccident: this.state.idTypesAccident,
                genders: this.state.idGender,
                tipoTerritorioSelecionado: 2,
                codigoTerritorioSelecionado: [11, 12, 13, 14, 15],
                tipoTerritorioAgrupamento: 2
            },
            cache: false,
            success: function (data) {
                //console.log('values-for-regions', data);
                this.setState({ valuesForRegions: data });
            }.bind(this),
            error: function (xhr, status, err) {
                console.log('erro');
            }.bind(this)
        });
    }

    loadValuesForUfs() {

        if (!this.state.start || !this.state.end) {
            return;
        }

        $.ajax({
            method: 'POST',
            url: "values-for-regions",
            data: {
                id: this.props.id,
                start: this.state.start,
                end: this.state.end,
                types: this.state.idTypes,
                typesAccident: this.state.idTypesAccident,
                genders: this.state.idGender,
                tipoTerritorioSelecionado: 3,
                codigoTerritorioSelecionado: [11, 12, 13, 14, 15, 16, 17, 23, 27, 29, 33, 35, 53, 21, 22, 24, 25, 26, 28, 31, 32, 41, 42, 43, 50, 51, 52],
                tipoTerritorioAgrupamento: 3
            },
            cache: false,
            success: function (data) {
                //console.log('values-for-regions', data);
                this.setState({ valuesForUfs: data });
            }.bind(this),
            error: function (xhr, status, err) {
                console.log('erro');
            }.bind(this)
        });
    }

    setCurrentPageListItems(page) {
        this.setState({ currentPageListItems: page }, function () {
            this.loadValues();
        });
    }

    loadValues() {
        if (!this.state.start || !this.state.end) {
            return;
        }

        $.ajax({
            method: 'POST',
            url: "pontos-transito-territorio",
            data: {
                serie_id: this.props.id,
                start: this.state.start,
                end: this.state.end,
                filters: this.state.filters,
                types: this.state.idTypes,
                typesAccident: this.state.idTypesAccident,
                genders: this.state.idGender,
                tipoTerritorioSelecionado: this.state.tipoTerritorioSelecionado, // tipo de territorio selecionado
                codigoTerritorioSelecionado: this.state.codigoTerritorioSelecionado, //codigo do territorio, que pode ser codigo do país, regiao, uf, etc...
                paginate: true,
                page: this.state.currentPageListItems
            },
            cache: false,
            success: function (data) {
                //console.log('load-values', data);
                this.setState({ values: data.valores });
            }.bind(this),
            error: function (xhr, status, err) {
                console.log('erro');
            }.bind(this)
        });
    }

    render() {

        //console.log('VALUES CHART FILTERS IN PAGE', this.state.valuesForChartFilters);

        let cards = this.state.valuesForChartFilters.map(function (item) {
            //console.log(item.values);

            return React.createElement(ChartBarHtml5, {
                chart: '1',
                type: '2',
                values: item.values,
                valuesSelected: '',
                icons: '',
                title: item.titulo
            });
        });

        /*cards = [
            <div>
                <p>aaaaaa</p>
                <p>aaaaaa</p>
                <p>aaaaaa</p>
                <p>aaaaaa</p>
                <p>aaaaaa</p>
                <p>aaaaaa</p>
            </div>,
            <div>bbbbbbbbb</div>,
            <div>ccccccccc</div>
        ];*/

        //console.log('CARDS', cards);

        return React.createElement(
            'div',
            null,
            React.createElement(
                'div',
                { className: 'container' },
                React.createElement(
                    'h1',
                    null,
                    'Acidentes de Transito'
                ),
                React.createElement('div', { className: 'line_title bg-pri' }),
                React.createElement('br', null),
                React.createElement('br', null),
                React.createElement(Filters, {
                    id: this.props.id,
                    checkFilter: this.checkFilter,
                    checkType: this.checkType,
                    iconsType: this.iconsType,
                    checkTypeAccident: this.checkTypeAccident,
                    checkGender: this.checkGender,
                    checkRegion: this.checkRegion,
                    checkYear: this.checkYear,
                    checkMonth: this.checkMonth,
                    actionFilter: this.actionFilter,
                    tipoTerritorioSelecionado: this.state.tipoTerritorioSelecionado,
                    codigoTerritorioSelecionado: this.state.codigoTerritorioSelecionado
                })
            ),
            React.createElement('br', null),
            React.createElement(Map, {
                id: this.props.id,
                filters: this.state.filters,
                types: this.state.idTypes,
                typesAccident: this.state.idTypesAccident,
                genders: this.state.idGender,
                tipoTerritorioSelecionado: this.state.tipoTerritorioSelecionado,
                codigoTerritorioSelecionado: this.state.codigoTerritorioSelecionado,
                tipoTerritorioAgrupamento: this.state.tipoTerritorioAgrupamento,
                typeIcons: this.state.typeIcons,
                filter: this.state.filter,
                actionFilter: this.actionFilter,
                start: this.state.start,
                end: this.state.end
                //year={this.state.year}
                //month={this.state.month}
            }),
            React.createElement('br', null),
            React.createElement('br', null),
            React.createElement('br', null),
            React.createElement('br', null),
            React.createElement(
                'div',
                { className: 'container' },
                React.createElement(Cards, {
                    cards: cards
                })
            ),
            React.createElement('br', null),
            React.createElement('br', null),
            React.createElement('br', null),
            React.createElement('br', null),
            React.createElement(
                'div',
                { className: 'container' },
                React.createElement(ChartBarHtml5, {
                    chart: '2',
                    type: '1',
                    height: '350px',
                    show: '3',
                    title: 'Regi\xF5es',
                    values: this.state.valuesForRegions
                })
            ),
            React.createElement('br', null),
            React.createElement('br', null),
            React.createElement('br', null),
            React.createElement('br', null),
            React.createElement(
                'div',
                { className: 'container' },
                React.createElement(ChartBarHtml5, {
                    chart: '3',
                    type: '1',
                    height: '350px',
                    show: '2',
                    values: this.state.valuesForUfs,
                    title: 'UF'
                })
            ),
            React.createElement('br', null),
            React.createElement('br', null),
            React.createElement('br', null),
            React.createElement('br', null),
            React.createElement(
                'div',
                { className: 'container' },
                React.createElement(ListItems, {
                    type: '1',
                    items: this.state.values,
                    setCurrentPageListItems: this.setCurrentPageListItems
                })
            )
        );
    }
}

ReactDOM.render(React.createElement(Page, { id: serie_id, default_regions: default_regions }), document.getElementById('page'));