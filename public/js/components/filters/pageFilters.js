class PageFilters extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            items: { data: [] },
            tema: props.tema_id,
            tipo: props.tipo,
            search: '',
            indicadores: [],
            abrangencias: [],
            currentPageListItems: 1,
            serieMarked: null,
            abrangencia: null,
            regions: [],
            periodos: [],
            from: null,
            to: null,
            loadingDefaultValues: false,
            loadingItems: false,
            limitItems: 20,
            serieIdDownload: 0,
            optionsAbrangencia: [{ id: 1, title: 'País', plural: ' os Países', on: false, listAll: 1, height: '250px' }, { id: 2, title: 'Região', plural: 'as Regiões', on: false, listAll: 1, height: '250px' }, { id: 3, title: 'UF', plural: 'os Estados', on: false, listAll: 1, height: '400px' }, { id: 7, title: 'Território', plural: 'os Estados', on: false, listAll: 1, height: '400px' }, { id: 4, title: 'Município', plural: 'os Municípios', on: false, listAll: 0, height: '400px',
                filter: [{ id: 12, title: 'Acre' }, { id: 27, title: 'Alagoas' }, { id: 13, title: 'Amazonas' }, { id: 16, title: 'Amapá' }, { id: 29, title: 'Bahia' }, { id: 23, title: 'Ceará' }, { id: 53, title: 'Distrito Federal' }, { id: 32, title: 'Espirito Santo' }, { id: 52, title: 'Goiás' }, { id: 21, title: 'Maranhão' }, { id: 50, title: 'Mato Grosso do Sul' }, { id: 51, title: 'Mato Grosso' }, { id: 31, title: 'Minas Gerais' }, { id: 15, title: 'Pará' }, { id: 41, title: 'Paraná' }, { id: 25, title: 'Paraíba' }, { id: 26, title: 'Pernambuco' }, { id: 22, title: 'Piauí' }, { id: 33, title: 'Rio de Janeiro' }, { id: 24, title: 'Rio Grande do Norte' }, { id: 43, title: 'Rio Grande do Sul' }, { id: 11, title: 'Rondônia' }, { id: 14, title: 'Roraima' }, { id: 42, title: 'Santa Catarina' }, { id: 35, title: 'São Paulo' }, { id: 28, title: 'Sergipe' }, { id: 17, title: 'Tocantins' }]

            }]
        };

        this.setTema = this.setTema.bind(this);
        this.setCurrentPageListItems = this.setCurrentPageListItems.bind(this);
        this.handleSearch = this.handleSearch.bind(this);
        this.checkIndicadores = this.checkIndicadores.bind(this);
        this.checkAbrangencias = this.checkAbrangencias.bind(this);
        this.selectSerie = this.selectSerie.bind(this);
        this.setRegions = this.setRegions.bind(this);
        this.loadPeriodos = this.loadPeriodos.bind(this);
        this.loadDefaultValues = this.loadDefaultValues.bind(this);
        this.loadRegions = this.loadRegions.bind(this);
        this.modalDownload = this.modalDownload.bind(this);
    }

    componentDidMount() {
        this.loadItems();
    }

    setTema(tema) {
        this.setState({
            tema: tema,
            items: [],
            indicadores: [],
            abrangencias: [],
            serieMarked: null,
            abrangencia: null,
            regions: [],
            periodos: [],
            from: null,
            to: null
        }, function () {
            this.loadItems();
        });
    }

    setCurrentPageListItems(page) {
        this.setState({ currentPageListItems: page }, function () {
            this.loadItems();
        });
    }

    handleSearch(e) {
        e.preventDefault();
        this.setState({ search: e.target.value }, function () {
            this.loadItems();
        });
    }

    checkIndicadores(types) {
        let ids = [];
        types.find(function (item) {
            ids.push(item.id);
        });
        this.setState({ indicadores: ids }, function () {
            this.loadItems();
        });
    }

    checkAbrangencias(types) {
        let ids = [];
        types.find(function (item) {
            ids.push(item.id);
        });
        this.setState({ abrangencias: ids }, function () {
            this.loadItems();
        });
    }

    loadItems() {
        //console.log(this.state);
        //console.log(this.state.currentPageListItems);
        let emptyItems = { data: [] };
        this.setState({ items: emptyItems, loadingItems: true });
        $.ajax({
            method: 'POST',
            url: "list-series",
            data: {
                parameters: {
                    tema_id: this.state.tema,
                    search: this.state.search,
                    indicadores: this.state.indicadores,
                    abrangencias: this.state.abrangencias,
                    limit: this.state.limitItems
                },
                page: this.state.currentPageListItems
            },
            cache: false,
            success: function (data) {
                //console.log('PAGEFILTER - ITEMS', data);
                //let items = {data: data};
                let items = data;

                this.setState({ items: items, loadingItems: false });
            }.bind(this),
            error: function (xhr, status, err) {
                console.log('erro');
            }.bind(this)
        });
    }

    selectSerie(item, all) {

        let optionsAbrangencia = this.state.optionsAbrangencia;

        optionsAbrangencia.find(function (option) {
            option.on = option.id === item.tipo_regiao;
        });

        //console.log('ITEM CLICADO', item);
        //console.log('OPTIONS ABRANGÊNCIAS', optionsAbrangencia);

        this.setState({ serieMarked: item.id, abrangencia: item.tipo_regiao }, function () {
            if (all) {
                this.setState({ loadingDefaultValues: true });
                this.loadDefaultValues();
                //this.submit();
                return;
            }

            this.loadPeriodos();
            $("#modalAbrangencias").modal();
        });
    }

    selectedAbrangencia() {
        let option = null;
        return this.state.optionsAbrangencia.find(function (op) {
            if (op.on) {
                option = op.id;
                return op;
            }
        });
        //return option;
    }

    setRegions(regions) {

        let regionsId = [];
        for (let i in regions) {
            regionsId.push(regions[i].id);
        }

        //console.log(regionsId);

        this.setState({ regions: regionsId });
    }

    loadDefaultValues() {
        this.loadPeriodos().then(function () {
            //console.log(this.state.periodos);
            this.loadRegions().then(function () {
                //console.log(this.state.regions);
                this.submit();
            }.bind(this));
        }.bind(this));
    }

    loadPeriodos() {
        return $.ajax("periodos/" + this.state.serieMarked + "/" + this.state.abrangencia, {
            data: {},
            success: function (data) {
                //console.log('range', data);
                this.setState({ periodos: data, from: data[0], to: data[data.length - 1] }, function () {});
            }.bind(this),
            error: function (data) {
                console.log('erro');
            }.bind(this)
        });
    }

    loadRegions() {
        return $.ajax({
            method: 'POST',
            url: 'territorios-serie-abrangencia',
            data: {
                id: this.state.serieMarked,
                abrangencia: this.state.abrangencia
            },
            cache: false,
            success: function (data) {
                //console.log('regions default', data);
                this.setState({ regions: data });
            }.bind(this),
            error: function (xhr, status, err) {
                console.log('erro', err);
            }.bind(this)
        });
    }

    submit() {
        $('#formFiltros').submit();
    }

    modalDownload(serieId) {
        this.setState({ serieIdDownload: serieId }, function () {
            $("#modalDownloads").modal();
        });
    }

    render() {

        let selectItems = React.createElement(SelectItems, {
            url: 'territorios',
            conditions: { id: this.state.serieMarked },
            option: this.selectedAbrangencia(),
            options: this.state.optionsAbrangencia,
            setItems: this.setRegions
        });

        let btnContinuar = React.createElement(
            'button',
            { type: 'button', className: 'btn btn-primary', onClick: () => this.submit(), disabled: true },
            'Continuar'
        );
        if (this.state.regions.length > 0 && this.state.periodos.length > 0 && this.state.from && this.state.to && this.state.abrangencia && this.state.serieMarked) {
            btnContinuar = React.createElement(
                'button',
                { type: 'button', className: 'btn btn-primary', onClick: () => this.submit() },
                'Continuar'
            );
        }

        //console.log('ITEMS', this.state.items);
        let items = this.state.items;
        if (!this.state.items.data) {
            items = { data: [] };
        }

        return React.createElement(
            'div',
            { className: 'container' },
            React.createElement(
                'h1',
                null,
                this.props.lang_inquiries
            ),
            React.createElement('br', null),
            React.createElement(
                'div',
                { className: 'row' },
                React.createElement(
                    'div',
                    { className: 'col-md-12' },
                    React.createElement(
                        'fieldset',
                        { style: { marginTop: '-15px' } },
                        React.createElement(
                            'legend',
                            null,
                            this.props.lang_themes
                        ),
                        React.createElement(
                            'div',
                            { style: { margin: '10px' } },
                            React.createElement(Temas, {
                                tipo: this.state.tipo,
                                tema_id: this.state.tema,
                                consulta_por_temas: this.props.consulta_por_temas,
                                setTema: this.setTema,
                                lang_select_themes: this.props.lang_select_themes
                            })
                        )
                    ),
                    React.createElement(
                        'fieldset',
                        { style: { display: this.props.filtroIndicadores == 0 ? 'none' : '' } },
                        React.createElement(
                            'legend',
                            null,
                            this.props.lang_documents
                        ),
                        React.createElement(
                            'div',
                            { style: { margin: '10px' } },
                            React.createElement(Filter, {
                                url: 'get-indicadores',
                                text: this.props.lang_search_indicators,
                                conditions: {
                                    tema_id: this.state.tema
                                },
                                checkType: this.checkIndicadores
                            })
                        )
                    )
                ),
                React.createElement(
                    'div',
                    { className: 'col-md-12' },
                    React.createElement('br', null),
                    React.createElement('input', { className: 'form-control', onChange: this.handleSearch, type: 'text', placeholder: this.props.lang_search_name }),
                    React.createElement('br', null),
                    React.createElement(
                        'div',
                        { className: 'text-center', style: { display: this.state.loadingItems ? '' : 'none' } },
                        React.createElement('i', { className: 'fa fa-spin fa-spinner fa-3x' })
                    ),
                    React.createElement(
                        'div',
                        { style: { display: items.data.length > 0 || this.state.loadingItems ? 'none' : '' }, className: 'no-results' },
                        React.createElement(
                            'h4',
                            null,
                            this.props.lang_no_results_title
                        ),
                        React.createElement(
                            'h5',
                            { style: { display: this.props.consulta_por_temas == 1 ? '' : 'none' } },
                            this.props.lang_no_results_subtitle
                        )
                    ),
                    React.createElement(
                        'div',
                        { style: { display: items.data.length > 0 ? '' : 'none' } },
                        React.createElement(List, {
                            items: items,
                            head: [this.props.lang_series, this.props.lang_unity, this.props.lang_frequency, '', ''],
                            showId: '0',
                            setCurrentPageListItems: this.setCurrentPageListItems,
                            currentPage: this.state.currentPageListItems,
                            perPage: '20',
                            select: this.selectSerie,
                            abrangencias: this.state.optionsAbrangencia,
                            urlDetailItem: 'dados-series',
                            modalDownload: this.modalDownload
                        })
                    )
                )
            ),
            React.createElement(Download, { serieId: this.state.serieIdDownload }),
            React.createElement(Modal, {
                id: 'modalAbrangencias',
                title: 'Selecione os Territ\xF3rios',
                body: selectItems,
                buttons: React.createElement(
                    'div',
                    null,
                    React.createElement(
                        'button',
                        { type: 'button', className: 'btn btn-default', 'data-dismiss': 'modal' },
                        'Cancelar'
                    ),
                    btnContinuar
                )
            }),
            React.createElement(
                'div',
                { style: {
                        position: 'fixed',
                        top: 0, right: 0, bottom: 0, left: 0,
                        backgroundColor: 'rgba(0, 0, 0, 0.7)',
                        width: '100%',
                        height: '100%',
                        zIndex: '99999999999999999',
                        display: this.state.loadingDefaultValues ? '' : 'none'
                    } },
                React.createElement(
                    'div',
                    { style: {
                            position: 'absolute',
                            top: 0,
                            right: 0,
                            bottom: 0, left: 0,
                            width: '400px',
                            height: '200px',
                            backgroundColor: '#fff',
                            border: 'solid 1px #ccc',
                            paddingTop: '60px',
                            margin: 'auto',
                            textAlign: 'center',
                            borderRadius: '5px'
                        }
                    },
                    React.createElement(
                        'h1',
                        null,
                        React.createElement('i', { className: 'fa fa-spinner fa-spin' }),
                        ' ',
                        this.props.lang_wait,
                        ' ...'
                    )
                )
            ),
            React.createElement(
                'form',
                { id: 'formFiltros', style: { display: 'none' }, action: 'dados-series', method: 'POST' },
                React.createElement('input', { type: 'hidden', name: '_token', value: $('meta[name="csrf-token"]').attr('content') }),
                React.createElement('input', { type: 'hidden', name: 'id', value: this.state.serieMarked }),
                React.createElement('input', { type: 'hidden', name: 'from', value: this.state.from }),
                React.createElement('input', { type: 'hidden', name: 'to', value: this.state.to }),
                React.createElement('input', { type: 'hidden', name: 'periodos', value: this.state.periodos }),
                React.createElement('input', { type: 'hidden', name: 'regions', value: this.state.regions }),
                React.createElement('input', { type: 'hidden', name: 'abrangencia', value: this.state.abrangencia })
            )
        );
    }
}

ReactDOM.render(React.createElement(PageFilters, {
    tipo: tipo,
    tema_id: tema_id,
    consulta_por_temas: consulta_por_temas,
    lang_inquiries: lang_inquiries,
    lang_themes: lang_themes,
    lang_series: lang_series,
    lang_documents: lang_documents,
    lang_search_indicators: lang_search_indicators,
    lang_search_name: lang_search_name,
    lang_unity: lang_unity,
    lang_frequency: lang_frequency,
    lang_no_results_title: lang_no_results_title,
    lang_no_results_subtitle: lang_no_results_subtitle,
    lang_wait: lang_wait,
    lang_select_themes: lang_select_themes,
    filtroIndicadores: filtroIndicadores
}), document.getElementById('filtros'));