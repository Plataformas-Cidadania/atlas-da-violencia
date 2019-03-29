class Filters extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            filtros: [],
            types: [],
            typesAccident: [],
            genders: [],
            regions: [],
            year: null,
            month: null,
            btnFilter: false,
            tipoTerritorioSelecionado: props.tipoTerritorioSelecionado
        };

        this.load = this.load.bind(this);

        this.checkFilter = this.checkFilter.bind(this);
        /*this.checkType = this.checkType.bind(this);
        this.checkTypeAccident = this.checkTypeAccident.bind(this);
        this.checkGender = this.checkGender.bind(this);*/
        this.checkRegion = this.checkRegion.bind(this);
        this.checkYear = this.checkYear.bind(this);
        this.checkMonth = this.checkMonth.bind(this);
        this.actionFilter = this.actionFilter.bind(this);
        this.enableBtnFilter = this.enableBtnFilter.bind(this);
        this.disableBtnFilter = this.disableBtnFilter.bind(this);
        this.iconsType = this.iconsType.bind(this);
    }

    componentDidMount() {
        this.load();
    }

    componentWillReceiveProps(props) {
        if (props.tipoTerritorioSelecionado != this.state.tipoTerritorioSelecionado) {
            this.setState({ tipoTerritorioSelecionado: props.tipoTerritorioSelecionado });
        }
    }

    load() {
        $.ajax({
            method: 'GET',
            url: 'filtros-serie/' + serie_id,
            data: {},
            cache: false,
            success: function (data) {
                //console.log(data);

                this.setState({ filtros: data, loading: false }, function () {
                    this.props.setSerieFilters(this.state.filtros);
                });
            }.bind(this),
            error: function (xhr, status, err) {
                console.error(status, err.toString());
                this.setState({ loading: false });
            }.bind(this)
        });
    }

    /*checkType(types){
        this.setState({types: types}, function(){
            this.props.checkType(this.state.types);
            this.enableBtnFilter();
        });
    }
     checkTypeAccident(types){
        this.setState({typesAccident: types}, function(){
            this.props.checkTypeAccident(this.state.typesAccident);
            this.enableBtnFilter();
        });
    }
     checkGender(types){
        this.setState({genders: types}, function(){
            this.props.checkGender(this.state.genders);
            this.enableBtnFilter();
        });
    }*/

    checkYear(year, enableBtnFilter = true) {
        this.setState({ year: year }, function () {
            this.props.checkYear(this.state.year);
            //console.log('BTN FILTER YEAR', enableBtnFilter);
            if (enableBtnFilter) {
                this.enableBtnFilter();
            }
        });
    }

    checkMonth(month, enableBtnFilter = true) {
        this.setState({ month: month }, function () {
            this.props.checkMonth(this.state.month);
            //console.log('BTN FILTER MONTH', enableBtnFilter);
            if (enableBtnFilter) {
                this.enableBtnFilter();
            }
        });
    }

    checkRegion(types, enableBtnFilter) {
        this.setState({ regions: types }, function () {
            this.props.checkRegion(this.state.regions);
            //console.log('BTN FILTER', enableBtnFilter);
            if (enableBtnFilter) {
                this.enableBtnFilter();
            }
        });
    }

    checkFilter(filterId, valuesFilterSelected) {
        //console.log(valuesFilterSelected);
        let filters = this.state.filtros;

        filters.map(function (item) {
            if (item.id == filterId) {
                item.valores = valuesFilterSelected;
            }
        });

        //console.log('FILTERS:', filters);
        this.setState({ filtros: filters }, function () {
            this.props.checkFilter(this.state.filtros);
            this.enableBtnFilter();
        });
    }

    actionFilter() {
        this.props.actionFilter();
        this.enableBtnFilter();
        this.disableBtnFilter();
    }

    iconsType(icons) {
        this.props.iconsType(icons);
    }

    enableBtnFilter() {
        this.setState({ btnFilter: true });
    }

    disableBtnFilter() {
        this.setState({ btnFilter: false });
    }

    render() {

        //console.log('BTN FILTER', this.state.btnFilter);
        //console.log('REGIONS', this.state.regions);

        let filters = this.state.filtros.map(function (item) {
            return React.createElement(
                'div',
                { className: 'col-md-3', key: 'filtro-' + item.id },
                React.createElement(
                    'fieldset',
                    null,
                    React.createElement(
                        'legend',
                        null,
                        item.titulo
                    ),
                    React.createElement(
                        'div',
                        { style: { margin: '10px' } },
                        React.createElement(Filter, { filter_id: item.id, checkFilter: this.checkFilter })
                    )
                )
            );
        }.bind(this));

        return React.createElement(
            'div',
            null,
            React.createElement(
                'div',
                { className: 'row' },
                React.createElement(
                    'div',
                    { className: 'col-md-6' },
                    React.createElement(RangeYear, { id: this.props.id, checkYear: this.checkYear })
                ),
                React.createElement('br', { className: 'hidden-lg hidden-md' }),
                React.createElement(
                    'div',
                    { className: 'col-md-1' },
                    '\xA0'
                ),
                React.createElement(
                    'div',
                    { className: 'col-md-5' },
                    React.createElement(RangeMonth, { id: this.props.id, checkMonth: this.checkMonth, year: this.state.year })
                )
            ),
            React.createElement('br', null),
            React.createElement(
                'div',
                { className: 'row' },
                React.createElement(
                    'div',
                    { className: 'col-md-3' },
                    React.createElement(
                        'fieldset',
                        null,
                        React.createElement(
                            'legend',
                            null,
                            'Regi\xE3o'
                        ),
                        React.createElement(
                            'div',
                            { style: { margin: '10px' } },
                            React.createElement(Region, {
                                checkRegion: this.checkRegion,
                                tipoTerritorioSelecionado: this.state.tipoTerritorioSelecionado,
                                codigoTerritorioSelecionado: this.props.codigoTerritorioSelecionado
                            })
                        )
                    )
                ),
                filters
            ),
            React.createElement('br', null),
            React.createElement(
                'div',
                { className: 'row' },
                React.createElement(
                    'div',
                    { className: 'col-md-12 text-center' },
                    React.createElement(
                        'button',
                        { className: 'btn btn-info', style: { width: "300px" }, disabled: !this.state.btnFilter, onClick: this.actionFilter },
                        'Filtrar'
                    )
                )
            )
        );
    }
}