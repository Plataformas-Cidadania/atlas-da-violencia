class Filters extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            types: [],
            typesAccident: [],
            genders: [],
            regions: [],
            year: null,
            month: null,
            btnFilter: false,
            tipoTerritorioSelecionado: props.tipoTerritorioSelecionado
        };

        this.checkType = this.checkType.bind(this);
        this.checkTypeAccident = this.checkTypeAccident.bind(this);
        this.checkGender = this.checkGender.bind(this);
        this.checkRegion = this.checkRegion.bind(this);
        this.checkYear = this.checkYear.bind(this);
        this.checkMonth = this.checkMonth.bind(this);
        this.actionFilter = this.actionFilter.bind(this);
        this.enableBtnFilter = this.enableBtnFilter.bind(this);
        this.disableBtnFilter = this.disableBtnFilter.bind(this);
    }

    componentWillReceiveProps(props) {
        if (props.tipoTerritorioSelecionado != this.state.tipoTerritorioSelecionado) {
            this.setState({ tipoTerritorioSelecionado: props.tipoTerritorioSelecionado });
        }
    }

    checkType(types) {
        this.setState({ types: types }, function () {
            this.props.checkType(this.state.types);
            this.enableBtnFilter();
        });
    }

    checkTypeAccident(types) {
        this.setState({ typesAccident: types }, function () {
            this.props.checkTypeAccident(this.state.typesAccident);
            this.enableBtnFilter();
        });
    }

    checkGender(types) {
        this.setState({ genders: types }, function () {
            this.props.checkGender(this.state.genders);
            this.enableBtnFilter();
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

    actionFilter() {
        this.props.actionFilter();
        this.enableBtnFilter();
        this.disableBtnFilter();
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

        return React.createElement(
            "div",
            null,
            React.createElement(
                "div",
                { className: "row" },
                React.createElement(
                    "div",
                    { className: "col-md-6" },
                    React.createElement(RangeYear, { checkYear: this.checkYear })
                ),
                React.createElement(
                    "div",
                    { className: "col-md-6" },
                    React.createElement(RangeMonth, { checkMonth: this.checkMonth })
                )
            ),
            React.createElement("br", null),
            React.createElement(
                "div",
                { className: "row" },
                React.createElement(
                    "div",
                    { className: "col-md-3" },
                    React.createElement(
                        "fieldset",
                        null,
                        React.createElement(
                            "legend",
                            null,
                            "Locomo\xE7\xE3o"
                        ),
                        React.createElement(
                            "div",
                            { style: { margin: '10px' } },
                            React.createElement(Type, { checkType: this.checkType })
                        )
                    )
                ),
                React.createElement(
                    "div",
                    { className: "col-md-3" },
                    React.createElement(
                        "fieldset",
                        null,
                        React.createElement(
                            "legend",
                            null,
                            "Tipo de Acidente"
                        ),
                        React.createElement(
                            "div",
                            { style: { margin: '10px' } },
                            React.createElement(TypeAccident, { checkTypeAccident: this.checkTypeAccident })
                        )
                    )
                ),
                React.createElement(
                    "div",
                    { className: "col-md-3" },
                    React.createElement(
                        "fieldset",
                        null,
                        React.createElement(
                            "legend",
                            null,
                            "Regi\xE3o"
                        ),
                        React.createElement(
                            "div",
                            { style: { margin: '10px' } },
                            React.createElement(Region, {
                                checkRegion: this.checkRegion,
                                tipoTerritorioSelecionado: this.state.tipoTerritorioSelecionado,
                                codigoTerritorioSelecionado: this.props.codigoTerritorioSelecionado
                            })
                        )
                    )
                ),
                React.createElement(
                    "div",
                    { className: "col-md-3" },
                    React.createElement(
                        "fieldset",
                        null,
                        React.createElement(
                            "legend",
                            null,
                            "Sexo"
                        ),
                        React.createElement(
                            "div",
                            { style: { margin: '10px' } },
                            React.createElement(Gender, { checkGender: this.checkGender })
                        )
                    )
                )
            ),
            React.createElement("br", null),
            React.createElement(
                "div",
                { className: "row" },
                React.createElement(
                    "div",
                    { className: "col-md-12 text-center" },
                    React.createElement(
                        "button",
                        { className: "btn btn-info", style: { width: "300px" }, disabled: !this.state.btnFilter, onClick: this.actionFilter },
                        "Filtrar"
                    )
                )
            )
        );
    }
}