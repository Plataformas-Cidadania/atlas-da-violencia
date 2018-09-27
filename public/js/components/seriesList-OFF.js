class SeriesList extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            loading: false,
            data: [],
            search: '',
            parameters: this.props.parameters,
            style: {
                marked: {
                    backgroundColor: '#E9F4E3'
                },
                unmarked: {
                    backgroundColor: '#fff'
                }
            },
            markedId: '',
            typerRegionSerie: '',
            tipo_valores: '',
            periodicidade: ''
        };

        this.loadData = this.loadData.bind(this);
        this.handleChange = this.handleChange.bind(this);
        this.marked = this.marked.bind(this);
    }

    componentDidMount() {
        this.loadData();
    }

    componentWillReceiveProps(props) {
        if (this.state.parameters.indicador != props.parameters.indicador) {
            this.setState({ parameters: props.parameters }, function () {
                this.loadData();
            });
        }
    }

    loadData() {
        this.setState({ loading: true });
        //console.log(this.state.parameters);
        $.ajax({
            method: 'POST',
            url: this.props.url,
            data: {
                search: this.state.search,
                parameters: this.state.parameters
            },
            cache: false,
            success: function (data) {
                //console.log('seriesList', data);
                this.setState({ data: data }, function () {
                    this.setState({ loading: false });
                });
            }.bind(this),
            error: function (xhr, status, err) {
                console.log('erro', err);
            }.bind(this)
        });
    }

    handleChange(e) {
        e.preventDefault();
        let value = e.target.value;
        //if(value.length > 2){
        this.setState({ search: value }, function () {
            this.loadData();
        });
        //}
    }

    marked(id, typeRegionSerie, tipoValores, periodicidade) {
        //console.log('marked', id, typeRegionSerie);
        this.setState({ markedId: id, typeRegionSerie: typeRegionSerie, tipoValores: tipoValores, periodicidade: periodicidade }, function () {
            this.props.serieMarked(this.state.markedId, this.state.typeRegionSerie, this.state.tipoValores, this.state.periodicidade);
        });
    }

    render() {
        let select1 = null;
        let select2 = null;
        let thCheck = null;
        let series = this.state.data.map(function (item, index) {
            //console.log('Titulo', item.titulo);

            if (this.props.select == 'link') {
                select1 = React.createElement(
                    'td',
                    null,
                    React.createElement(
                        'a',
                        { href: "filtros/" + item.id + "/" + item.titulo },
                        item.titulo
                    )
                );
            }
            if (this.props.select == 'mark-one') {
                thCheck = React.createElement(
                    'th',
                    null,
                    '\xA0'
                );
                select1 = React.createElement(
                    'td',
                    { onClick: () => this.marked(item.id, item.tipo_regiao, item.tipo_valores, item.periodicidade), style: { cursor: 'pointer' }, width: 20 },
                    React.createElement(
                        'a',
                        null,
                        React.createElement('img', { src: "img/checkbox_" + (item.id == this.state.markedId ? 'on' : 'off') + ".png", alt: '' })
                    )
                );
                select2 = React.createElement(
                    'td',
                    { onClick: () => this.marked(item.id, item.tipo_regiao, item.tipo_valores, item.periodicidade), style: { cursor: 'pointer' } },
                    React.createElement(
                        'a',
                        null,
                        item.titulo
                    )
                );
            }
            /*if(this.props.select == 'mark-several'){
             }*/
            return React.createElement(
                'tr',
                { key: "s_" + item.id, style: item.id == this.state.markedId ? this.state.style.marked : this.state.style.unmarked },
                select1,
                select2,
                React.createElement(
                    'td',
                    null,
                    item.titulo_unidade
                ),
                React.createElement(
                    'td',
                    null,
                    item.periodicidade
                ),
                React.createElement(
                    'td',
                    { width: '100' },
                    formatPeriodicidade(item.min, item.periodicidade),
                    ' - ',
                    formatPeriodicidade(item.max, item.periodicidade)
                )
            );
        }.bind(this));

        return React.createElement(
            'div',
            null,
            React.createElement(
                'h4',
                null,
                'Selecione abaixo a s\xE9rie que deseja visualizar'
            ),
            React.createElement('input', { type: 'text', className: 'form-control', placeholder: 'Pesquisa', onChange: this.handleChange }),
            React.createElement('br', null),
            React.createElement(
                'div',
                { className: 'text-center', style: { display: this.state.loading ? 'block' : 'none' } },
                React.createElement(
                    'i',
                    { className: 'fa fa-4x fa-spinner fa-spin' },
                    ' '
                )
            ),
            React.createElement(
                'div',
                { style: { display: this.state.loading ? 'none' : 'block' } },
                React.createElement(
                    'table',
                    { className: 'table table-bordered table-hover table-responsive' },
                    React.createElement(
                        'thead',
                        null,
                        React.createElement(
                            'tr',
                            null,
                            thCheck,
                            React.createElement(
                                'th',
                                null,
                                'S\xE9rie'
                            ),
                            React.createElement(
                                'th',
                                null,
                                'Unidade'
                            ),
                            React.createElement(
                                'th',
                                null,
                                'Frequ\xEAncia'
                            ),
                            React.createElement(
                                'th',
                                null,
                                'Per\xEDodo'
                            )
                        )
                    ),
                    React.createElement(
                        'tbody',
                        null,
                        series
                    )
                )
            )
        );
    }
}